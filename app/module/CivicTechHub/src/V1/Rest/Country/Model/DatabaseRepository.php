<?php


namespace CivicTechHub\V1\Rest\Country\Model;


use Application\Model\AbstractDatabaseRepository;
use CivicTechHub\V1\Rest\Country\CountryEntity;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    public function fetchById(int $id)
    {
        $where = new Where();
        $where->equalTo('country.id', $id);

        $select = $this->buildSelectIncludingCountGroups();
        $select->where($where);

        $country = $this->fetchEntityWithSelect($select);
        return $country;
    }

    public function fetchAllByIdsOrderedByName(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $where = new Where();
        $where->in('country.id', $ids);

        $select = $this->buildSelectIncludingCountGroups();
        $select->where($where);
        $select->order('country.name');

        /** @var CountryEntity[] $countries */
        $countries = $this->fetchAllEntitiesWithSelect($select);

        return $countries;
    }

    public function fetchForSearchphrase(string $searchphrase, int $limit): array
    {
        $where = new Where();
        $where->like('country.name', '%' . $searchphrase . '%');

        $select = $this->buildSelectIncludingCountGroups();
        $select->where($where);
        $select->order(['count_groups' => 'DESC']);
        $select->limit($limit);

        return $this->fetchAllEntitiesWithSelect($select);
    }

    private function buildSelectIncludingCountGroups()
    {
        $select = new Select('group');
        $select->join('country', 'country.id = group.country_id', Select::SQL_STAR, Select::JOIN_LEFT);
        $select->columns([
            'id' => 'group.country_id',
            'name' => 'country.name',
            'iso_3166_code' => 'country.iso_3166_code',
            'count_groups' => new Expression('count(*)')
        ], false);
        $select->group('group.country_id');

        return $select;
    }
}