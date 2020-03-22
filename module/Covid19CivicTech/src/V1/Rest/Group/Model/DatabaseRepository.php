<?php


namespace Covid19CivicTech\V1\Rest\Group\Model;


use Application\Model\AbstractDatabaseRepository;
use Laminas\Db\Sql\Select;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
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
}