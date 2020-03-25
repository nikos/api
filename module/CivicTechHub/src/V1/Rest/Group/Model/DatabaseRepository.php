<?php

namespace CivicTechHub\V1\Rest\Group\Model;


use Application\Model\AbstractDatabaseRepository;
use CivicTechHub\V1\Rest\Group\GroupCollection;
use CivicTechHub\V1\Rest\Group\GroupEntity;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    private $collectionFilters = [];

    private $collectionCountriesIndexedById = [];
    private $collectionTopicsIndexedByGroupId = [];
    private $collectionServiceLinksIndexedByGroupId = [];

    /**
     * @var \CivicTechHub\V1\Rest\Country\Model\RepositoryInterface
     */
    private $countryRepository;
    /**
     * @var \CivicTechHub\V1\Rest\Topic\Model\RepositoryInterface
     */
    private $topicRepository;
    /**
     * @var \CivicTechHub\V1\Rest\ServiceLink\Model\RepositoryInterface
     */
    private $serviceLinkRepository;

    public function __construct(
        TableGateway $tableGateway,
        \CivicTechHub\V1\Rest\Country\Model\RepositoryInterface $countryRepository,
        \CivicTechHub\V1\Rest\Topic\Model\RepositoryInterface $topicRepository,
        \CivicTechHub\V1\Rest\ServiceLink\Model\RepositoryInterface $serviceLinkRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->topicRepository = $topicRepository;
        $this->serviceLinkRepository = $serviceLinkRepository;

        parent::__construct($tableGateway);
    }

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

    public function fetchById(int $id)
    {
        $group = $this->fetchEntityByFieldValue('id', $id);

        if (empty($group)) {
            return $group;
        }

        /* @var GroupEntity $group */
        if (! empty($group->countryId)) {
            $group->country = $this->countryRepository->fetchById($group->countryId);
        }

        $topics = $this->topicRepository->fetchAllForGroupIdsIndexedByGroupId([$group]);
        if (! empty($topics[$group->id])) {
            $group->topics = $topics[$group->id];
        }

        $serviceLinks = $this->serviceLinkRepository->fetchAllByGroupId($group->id);
        if (! empty($serviceLinks)) {
            $group->serviceLinks = $serviceLinks;
        }

        return $group;
    }

    public function addFilterByCountryForCollection(int $countryId)
    {
        $this->collectionFilters['countryId'] = $countryId;
    }

    public function addFilterByTopicsForCollection(array $topicIds)
    {
        $this->collectionFilters['topicIds'] = $topicIds;
    }

    public function getCollection(): GroupCollection
    {
        $select = $this->getSelect();
        $select->where($this->buildWhereForCollection());

        $this->collectionCountriesIndexedById = [];
        $paginatorAdapter = $this->getEnhanceableItemPaginatorAdapterForSelect($select);
        $paginatorAdapter->setBeforeEnhanceItemsCallback(function ($groups) {
            $this->collectionCountriesIndexedById = $this->fetchAllCountriesIndexedByIdForGroups($groups);

            $groupIds = array_map(function (GroupEntity $group) {
                return $group->id;
            }, $groups);
            $this->collectionTopicsIndexedByGroupId = $this->topicRepository->fetchAllForGroupIdsIndexedByGroupId($groupIds);
            $this->collectionServiceLinksIndexedByGroupId = $this->serviceLinkRepository->fetchAllForGroupIdsIndexedByGroupId($groupIds);
        });
        $paginatorAdapter->setEnhanceItemFunction(function(GroupEntity $group) {
            if ($group->countryId && isset($this->collectionCountriesIndexedById[$group->countryId])) {
                $group->country = $this->collectionCountriesIndexedById[$group->countryId];
            }
            if (! empty($this->collectionTopicsIndexedByGroupId[$group->id])) {
                $group->topics = $this->collectionTopicsIndexedByGroupId[$group->id];
            }
            if (! empty($this->collectionServiceLinksIndexedByGroupId[$group->id])) {
                $group->serviceLinks = $this->collectionServiceLinksIndexedByGroupId[$group->id];
            }
            return $group;
        });

        return new GroupCollection($paginatorAdapter);
    }

    public function fetchListWithIdAndNameForSearchphrase(string $searchphrase): array
    {
        $select = $this->getSelect();
        $select->columns(['id', 'name']);
        $select->where((new Where())->like('name', '%' . $searchphrase . '%'));
        $select->order(['name' => 'ASC']);

        return $this->fetchRowsWithSelect($select);
    }

    private function buildWhereForCollection()
    {
        $where = new Where();

        if (isset($this->collectionFilters['countryId'])) {
            $where->equalTo('country_id', $this->collectionFilters['countryId']);
        }
        if (! empty($this->collectionFilters['topicIds'])) {
            $groupIds = $this->topicRepository->fetchAllGroupIdsForTopicIds($this->collectionFilters['topicIds']);
            $where->in('id', $groupIds);
        }

        return $where;
    }

    /**
     * @param GroupEntity[] $groups
     */
    private function fetchAllCountriesIndexedByIdForGroups(array $groups)
    {
        $countryIds = array_filter(array_map(function (GroupEntity $group) {
            return $group->countryId;
        }, $groups));

        $countries = $this->countryRepository->fetchAllByIdsOrderedByName($countryIds);
        $countriesIndexedById = [];
        foreach ($countries as $country) {
            $countriesIndexedById[$country->id] = $country;
        }

        return $countriesIndexedById;
    }
}