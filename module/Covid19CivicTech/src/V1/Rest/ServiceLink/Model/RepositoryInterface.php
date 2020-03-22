<?php
namespace Covid19CivicTech\V1\Rest\ServiceLink\Model;


use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkCollection;
use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkEntity;

interface RepositoryInterface
{
    /**
     * @param int $id
     * @return ServiceLinkEntity|null
     */
    public function fetchById(int $id);

    /**
     * @param int $groupId
     * @return ServiceLinkEntity[]
     */
    public function fetchAllByGroupId(int $groupId);

    /**
     * @param array $groupIds
     * @return ServiceLinkEntity[]
     */
    public function fetchAllForGroupIdsIndexedByGroupId($groupIds);

    public function getCollection(): ServiceLinkCollection;
}