<?php


namespace CivicTechHub\V1\Rest\Topic\Model;

use Application\Model\AbstractDatabaseRepository;
use CivicTechHub\V1\Rest\Topic\TopicEntity;
use Laminas\Db\Sql\Select;
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

    public function fetchAllForGroupIdsIndexedByGroupId(array $groupIds)
    {
        $topicIdsGroupedByGroupId = $this->fetchAllTopicIdsForGroupIdsGroupedByGroupId($groupIds);
        if (empty($topicIdsGroupedByGroupId)) {
            return [];
        }

        $allTopicIds = array_reduce($topicIdsGroupedByGroupId, function(array $carry, array $topicIds) {
            return array_merge($carry, $topicIds);
        }, []);

        $allTopicsIndexedById = $this->fetchTopicsIndexById($allTopicIds);

        $topicsIndexedByGroupId = [];
        foreach ($topicIdsGroupedByGroupId as $groupId => $topicIds) {
            $topicsIndexedByGroupId[$groupId] = [];
            foreach ($topicIds as $topicId) {
                $topicsIndexedByGroupId[$groupId][] = $allTopicsIndexedById[$topicId];
            }
        }

        return $topicsIndexedByGroupId;
    }

    public function fetchAllGroupIdsForTopicIds($topicIds)
    {
        if (empty($topicIds)) {
            return [];
        }

        $where = new Where();
        $where->in('topic_id', $topicIds);

        $select = new Select('group_topic');
        $select->columns(['group_id']);
        $select->where($where);
        $select->quantifier(Select::QUANTIFIER_DISTINCT);

        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        $groupIds = [];
        foreach ($rows as $row) {
            $groupIds[] = $row['group_id'];
        }

        return $groupIds;
    }

    private function fetchAllTopicIdsForGroupIdsGroupedByGroupId(array $groupIds)
    {
        if (empty($groupIds)) {
            return [];
        }

        $select = new Select('group_topic');
        $select->columns(['group_id', 'topic_id']);

        $where = new Where();
        $where->in('group_id', $groupIds);

        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        $topicIds = [];
        foreach ($rows as $row) {
            if (empty($topicIds[$row['group_id']])) {
                $topicIds[$row['group_id']] = [];
            }
            $topicIds[$row['group_id']][] = $row['topic_id'];
        }

        return $topicIds;
    }

    private function fetchTopicsIndexById(array $topicIds)
    {
        $where = new Where();
        $where->in('id', $topicIds);

        $topics = $this->fetchAllEntitiesWithWhere($where);

        $topicsIndexedById = [];
        foreach ($topics as $topic) {
            $topicsIndexedById[$topic->id] = $topic;
        }

        return $topicsIndexedById;
    }
}