<?php


namespace Covid19CivicTech\V1\Rest\Topic\Model;

use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\Topic\TopicEntity;
use Laminas\Db\Sql\Where;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    public function fetchById(int $id)
    {
        return $this->fetchEntityByFieldValue('id', $id);
    }

    public function fetchAll()
    {
        return $this->fetchAllEntities();
    }

    public function fetchAllForGroupIds(array $groupIds)
    {
        $where = new Where();

    }
}