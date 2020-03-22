<?php


namespace Covid19CivicTech\V1\Rest\Country\Model;


use Application\Model\AbstractDatabaseRepository;
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

        return $this->fetchAllEntitiesWithSelect($select);
    }


}