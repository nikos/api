<?php


namespace Covid19CivicTech\V1\Rest\Country\Model;


use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\Country\CountryEntity;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    public function fetchAllOrderedByName()
    {
        $select = $this->getSelect();
        $select->order('name');

        return $this->fetchAllEntitiesWithSelect($select);
    }

    public function fetchById(int $id)
    {
        return $this->fetchEntityByFieldValue('id', $id);
    }

    public function fetchAllByIds(array $ids)
    {
        // TODO: Implement fetchAllByIds() method.
    }


}