<?php
namespace Covid19CivicTech\V1\Rest\Topic\Model;

use Covid19CivicTech\V1\Rest\Topic\TopicEntity;

interface RepositoryInterface
{
    /**
     * @param int $id
     * @return TopicEntity|null
     */
    public function fetchById(int $id);

    /**
     * @return TopicEntity[]
     */
    public function fetchAll();

    /**
     * @param array $groupIds
     * @return TopicEntity[]
     */
    public function fetchAllForGroupIdsIndexedByGroupId(array $groupIds);

    /**
     * @param int[] $topicIds
     * @return int[]
     *
     */
    public function fetchAllGroupIdsForTopicIds($topicIds);
}