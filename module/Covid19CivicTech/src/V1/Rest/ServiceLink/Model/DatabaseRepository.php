<?php

namespace Covid19CivicTech\V1\Rest\ServiceLink\Model;


use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkCollection;
use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkEntity;
use Laminas\Db\Sql\Where;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    public function fetchById(int $id)
    {
        return $this->fetchEntityByFieldValue('id', $id);
    }

    public function fetchAllByGroupId(int $groupId)
    {
        $where = new Where();
        $where->equalTo('group_id', $groupId);

        return $this->fetchAllEntitiesWithWhere($where);
    }

    public function fetchAllForGroupIdsIndexedByGroupId($groupIds)
    {
        $where = new Where();
        $where->in('group_id', $groupIds);

        $serviceLinks = $this->fetchAllEntitiesWithWhere($where);
        $serviceLinksIndexedByGroupId = [];
        foreach ($serviceLinks as $serviceLink) {
            if (empty($serviceLinksIndexedByGroupId[$serviceLink->groupId])) {
                $serviceLinksIndexedByGroupId[$serviceLink->groupId] = [];
            }

            $serviceLinksIndexedByGroupId[$serviceLink->groupId][] = $serviceLink;
        }

        return $serviceLinksIndexedByGroupId;
    }

    public function getCollection(): ServiceLinkCollection
    {
        return new ServiceLinkCollection($this->getPaginatorAdapterForSelect($this->getSelect()));
    }
}