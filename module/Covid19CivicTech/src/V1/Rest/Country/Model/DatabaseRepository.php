<?php


namespace Covid19CivicTech\V1\Rest\Country\Model;


use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\Country\CountryEntity;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    public function fetchById(int $id)
    {
        return $this->fetchEntityByFieldValue('id', $id);
    }

    public function fetchAllByIdsOrderedByName(array $ids)
    {
        $where = new Where();

        if (! empty($ids)) {
            $where->in('id', $ids);
        }

        $select = $this->getSelect();
        $select->where($where);
        $select->order('name');

        /** @var CountryEntity[] $countries */
        $countries = $this->fetchAllEntitiesWithSelect($select);
        $countGroupsIndexedByCountryId = $this->fetchCountGroupsIndexedByCountryId($ids);

        foreach ($countries as $country) {
            $country->countGroups = $countGroupsIndexedByCountryId[$country->id] ?? 0;
        }

        return $countries;
    }

    /**
     * @param CountryEntity[] $countries
     * @param int[] $countryIds
     */
    private function fetchCountGroupsIndexedByCountryId($countryIds)
    {
        $select = new Select('group');
        $select->columns(['country_id', 'countGroups' => new Expression('count(*)')]);
        $select->group('country_id');

        $where = new Where();
        $where->in('country_id', $countryIds);

        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        $countGroupsIndexedByCountryId = [];
        foreach ($rows as $row) {
            $countGroupsIndexedByCountryId[$row['country_id']] = $row['countGroups'];
        }

        return $countGroupsIndexedByCountryId;
    }

}