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

    private $embeddDependencyCountry = false;
    private $embeddDependencyServiceLinks = false;
    private $embeddDependencyTopics = false;

    private $dedendencyMapForCountries = [];
    private $dependencyMapForTopics = [];
    private $dependencyMapForServiceLinks = [];

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

    public function embeddAllDependencies()
    {
        $this->embeddCountryAsDependency();
        $this->embeddServiceLinksAsDependency();
        $this->embeddTopicsAsDependency();
    }

    public function embeddCountryAsDependency()
    {
        $this->embeddDependencyCountry = true;
    }

    public function embeddServiceLinksAsDependency()
    {
        $this->embeddDependencyServiceLinks = true;
    }

    public function embeddTopicsAsDependency()
    {
        $this->embeddDependencyTopics = true;
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

        $this->collectDependenciesForGroups([$group]);
        $this->setDependenciesInGroupEntity($group);

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

        $this->dedendencyMapForCountries = [];
        $paginatorAdapter = $this->getEnhanceableItemPaginatorAdapterForSelect($select);
        $paginatorAdapter->setBeforeEnhanceItemsCallback(function ($groups) {
            $this->collectDependenciesForGroups($groups);
        });
        $paginatorAdapter->setEnhanceItemFunction(function(GroupEntity $group) {
            $this->setDependenciesInGroupEntity($group);
            return $group;
        });

        return new GroupCollection($paginatorAdapter);
    }

    public function fetchForSearchphrase(string $searchphrase, int $limit): array
    {
        $select = $this->getSelect();
        $select->where((new Where())->like('name', '%' . $searchphrase . '%'));
        $select->order(['name' => 'ASC']);
        $select->limit($limit);

        $groups = $this->fetchAllEntitiesWithSelect($select);

        if (empty($groups)) {
            return [];
        }

        $this->collectDependenciesForGroups($groups);
        foreach($groups as $group) {
            $this->setDependenciesInGroupEntity($group);
        }

        return $groups;
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

    /**
     * @param GroupEntity[] $groups
     */
    private function collectDependenciesForGroups(array $groups)
    {
        if ($this->embeddDependencyCountry) {
            $this->dedendencyMapForCountries = $this->fetchAllCountriesIndexedByIdForGroups($groups);
        }

        if (! $this->embeddDependencyTopics && ! $this->embeddDependencyServiceLinks) {
            return;
        }

        $groupIds = array_map(function (GroupEntity $group) {
            return $group->id;
        }, $groups);
        if ($this->embeddDependencyServiceLinks) {
            $this->dependencyMapForServiceLinks = $this->serviceLinkRepository->fetchAllForGroupIdsIndexedByGroupId($groupIds);
        }
        if ($this->embeddDependencyTopics) {
            $this->dependencyMapForTopics = $this->topicRepository->fetchAllForGroupIdsIndexedByGroupId($groupIds);
        }
    }

    private function setDependenciesInGroupEntity(GroupEntity $group)
    {
        if ($this->embeddDependencyCountry && $group->countryId && isset($this->dedendencyMapForCountries[$group->countryId])) {
            $group->country = $this->dedendencyMapForCountries[$group->countryId];
        }
        if ($this->embeddDependencyServiceLinks && ! empty($this->dependencyMapForServiceLinks[$group->id])) {
            $group->serviceLinks = $this->dependencyMapForServiceLinks[$group->id];
        }
        if ($this->embeddDependencyTopics && ! empty($this->dependencyMapForTopics[$group->id])) {
            $group->topics = $this->dependencyMapForTopics[$group->id];
        }
    }
}