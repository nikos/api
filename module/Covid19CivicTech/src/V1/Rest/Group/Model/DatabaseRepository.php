<?php


namespace Covid19CivicTech\V1\Rest\Group\Model;


use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\Group\GroupCollection;
use Covid19CivicTech\V1\Rest\Group\GroupEntity;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    private $filters = [];
    /**
     * @var \Covid19CivicTech\V1\Rest\Country\Model\RepositoryInterface
     */
    private $countryRepository;

    public function __construct(TableGateway $tableGateway, \Covid19CivicTech\V1\Rest\Country\Model\RepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;

        parent::__construct($tableGateway);
    }

    public function fetchAllCountryIds(): array
    {
        $select = $this->getSelect();
        $select->columns(['country_id']);
        $select->quantifier(Select::QUANTIFIER_DISTINCT);

        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        $countryIds = [];
        foreach ($rows as $row) {
            $countryIds[] = $row['country_id'];
        }

        return $countryIds;
    }

    public function fetchById(int $id)
    {
        $group = $this->fetchEntityByFieldValue('id', $id);

        if (empty($group)) {
            return $group;
        }

        /* @var GroupEntity $group */
        if (! empty($group->countryId)) {
            $group->country = $this->countryRepository->fetchById($group->countryId);
        }

        return $group;
    }

    public function addFilterByCountryForCollection(int $countryId)
    {
        $this->filters['country_id'] = $countryId;
    }

    public function getCollection(): GroupCollection
    {
        $select = $this->getSelect();
        $select->where($this->buildWhereForCollection());

        return new GroupCollection($this->getPaginatorAdapterForSelect($select));
    }

    private function buildWhereForCollection()
    {
        $where = new Where();

        if (isset($this->filters['country_id'])) {
            $where->equalTo('country_id', $this->filters['country_id']);
        }

        return $where;
    }


}