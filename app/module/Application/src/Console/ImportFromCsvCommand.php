<?php


namespace Application\Console;


use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportFromCsvCommand extends Command
{
    const ARRAY_DELIMITER = '|||';
    const SERVICE_LINK_DELIMITER = '>>>';

    protected static $defaultName = 'import-from-csv';
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;

    private $countryCodeToCountryIdMap = [];

    private $topicToTopicIdMap = [];

    private $countError = 0;
    private $countSuccess = 0;
    private $countDuplicates = 0;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('input_file', InputArgument::REQUIRED, 'Path to the csv file to import from.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildCountryCodeToCountryIdMap();
        $this->buildTopicToTopicIdMap();
        $this->countSuccess = 0;
        $this->countError = 0;
        $this->countDuplicates = 0;

        $this->readAndPersistDataFromInputFile($input->getArgument('input_file'), $output);

        $output->writeln($this->countSuccess .' rows imported.');
        if ($this->countDuplicates) {
            $output->writeln($this->countDuplicates . ' duplicates not imported.');
        }
        if ($this->countError) {
            $output->writeln($this->countError . ' rows had errors and could not be imported.');
        }

        return 0;
    }

    private function readAndPersistDataFromInputFile($filePath, OutputInterface $output)
    {
        if (! file_exists($filePath)) {
            throw new \RuntimeException('File ' . $filePath . ' not found.');
        }
        $fileHandle = fopen($filePath, 'r');
        if ($fileHandle === false) {
            throw new \RuntimeException('Could not open file ' . $filePath . '.');
        }
        // skip first row
        $data = fgetcsv($fileHandle, 0, ';', '"');

        do {
            $data = fgetcsv($fileHandle, 0, ';', '"');
            if ($data === false || $data === null) {
                break;
            }

            try {
                $this->persistDataForEntry($data);
            } catch (\Throwable $exception) {
                $output->writeln('Error: ' .$exception->getMessage());
                $output->writeln($exception);
                $this->countError++;
            }

        } while (true);
    }

    private function persistDataForEntry($data)
    {
        if (!isset($this->countryCodeToCountryIdMap[$data[2]])) {
            throw new \RuntimeException('Could not find country with code ' . $data[2]);
        }

        if (empty($data[0])) {
            throw new \RuntimeException('Group name is missing.');
        }
        if (empty($data[3])) {
            throw new \RuntimeException('Group description is missing.');
        }

        $group = [
            'name' => $this->encodeString($data[0]),
            'description' => $this->encodeString($data[3]),
            'country_id' => empty($data[2]) ? null : $this->countryCodeToCountryIdMap[$data[2]]
        ];

        if ($this->groupExists($group['name'])) {
            $this->countDuplicates++;
            return;
        }

        $groupId = $this->persistsGroupAndReturnId($group);

        if (! empty($data[4])) {
            $this->persistTopicsAndAssignToGroup($data[4], $groupId);
        }
        if (! empty($data[5])) {
            $this->persistServiceLinks($data[5], $groupId);
        }

        $this->countSuccess++;
    }

    private function persistsGroupAndReturnId($group)
    {
        $sql = new Sql($this->dbAdapter);
        $insert = $sql->insert('group');
        $insert->values($group);

        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        return $result->getGeneratedValue();
    }

    public function persistTopicsAndAssignToGroup($topics, $groupId)
    {
        if (empty($topics)) {
            return;
        }

        $topics = explode(self::ARRAY_DELIMITER, $topics);
        foreach ($topics as $topic) {
            if (! isset($this->topicToTopicIdMap[$topic])) {
                $this->persistTopicAndAddToTopicMap($topic);
            }

            $this->assignTopicToGroup($this->topicToTopicIdMap[$topic], $groupId);
        }
    }

    public function persistServiceLinks($serviceLinks, $groupId)
    {
        if (empty($serviceLinks)) {
            return;
        }

        $serviceLinks = explode(self::ARRAY_DELIMITER, $serviceLinks);
        foreach ($serviceLinks as $serviceLink) {
            $serviceLinksParts = explode(self::SERVICE_LINK_DELIMITER, $serviceLink);
            if (empty($serviceLinksParts)) {
                continue;
            }
            if (count($serviceLinksParts) !== 3) {
                throw new \RuntimeException('Service link ' . $serviceLink . ' is missing fields!');
            }

            $this->persistServiceLink($serviceLinksParts[0], $serviceLinksParts[1], $serviceLinksParts[2], $groupId);
        }
    }

    private function persistTopicAndAddToTopicMap($topic)
    {
        $sql = new Sql($this->dbAdapter);
        $insert = $sql->insert('topic');
        $insert->values(['name' => $topic]);

        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        $this->topicToTopicIdMap[$topic] = $result->getGeneratedValue();
    }

    private function assignTopicToGroup($topicId, $groupId)
    {
        $sql = new Sql($this->dbAdapter);
        $insert = $sql->insert('group_topic');
        $insert->values(['topic_id' => $topicId, 'group_id' => $groupId]);

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    private function persistServiceLink($linkText, $linkUrl, $linkType, $groupId)
    {
        $sql = new Sql($this->dbAdapter);
        $insert = $sql->insert('service_link');
        $insert->values([
            'text' => $linkText,
            'url' => $linkUrl,
            'group_id' => $groupId,
            'type' => $linkType
        ]);

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    private function buildCountryCodeToCountryIdMap()
    {
        $rows = $this->dbAdapter->getDriver()->getConnection()->execute('Select * from country;');
        $this->countryCodeToCountryIdMap = [];
        foreach ($rows as $row) {
            $this->countryCodeToCountryIdMap[$row['iso_3166_code']] = $row['id'];
        }
    }

    private function buildTopicToTopicIdMap()
    {
        $rows = $this->dbAdapter->getDriver()->getConnection()->execute('Select * from topic;');
        $this->topicToTopicIdMap = [];
        foreach ($rows as $row) {
            $this->topicToTopicIdMap[$row['name']] = $row['id'];
        }
    }

    private function groupExists($name)
    {
        $sql = new Sql($this->dbAdapter);

        $where = new Where();
        $where->equalTo('name', $name);

        $select = new Select('group');
        $select->columns(['count' => new Expression('count(*)')]);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();

        if (empty($row) || ! isset($row['count'])) {
            throw new \RuntimeException('Could not fetch count.');
        }
        $count = (int)$row['count'];

        if ($count > 0) {
            return true;
        }

        return false;
    }

    private function encodeString($value)
    {
        return utf8_encode($value);
    }
}