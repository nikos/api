<?php

namespace Covid19CivicTech\V1\Rest\Group\Model;


use Application\Model\AbstractDatabaseRepository;
use Covid19CivicTech\V1\Rest\Group\GroupCollection;
use Covid19CivicTech\V1\Rest\Group\GroupEntity;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    private $collectionFilters = [];

    private $collectionCountriesIndexedById = [];

    /**
     * @var \Covid19CivicTech\V1\Rest\Country\Model\RepositoryInterface
     */
    private $countryRepository;

    public function __construct(TableGateway $tableGateway, \Covid19CivicTech\V1\Rest\Country\Model\RepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;

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

        return $group;
    }

    public function addFilterByCountryForCollection(int $countryId)
    {
        $this->collectionFilters['country_id'] = $countryId;
    }

    public function getCollection(): GroupCollection
    {
        $select = $this->getSelect();
        $select->where($this->buildWhereForCollection());

        $this->collectionCountriesIndexedById = [];
        $paginatorAdapter = $this->getEnhanceableItemPaginatorAdapterForSelect($select);
        $paginatorAdapter->setBeforeEnhanceItemsCallback(function ($groups) {
            $this->collectionCountriesIndexedById = $this->fetchAllCountriesIndexedByIdForGroups($groups);
        });
        $paginatorAdapter->setEnhanceItemFunction(function(GroupEntity $group) {
            if ($group->countryId && isset($this->collectionCountriesIndexedById[$group->countryId])) {
                $group->country = $this->collectionCountriesIndexedById[$group->countryId];
            }
            return $group;
        });

        return new GroupCollection($paginatorAdapter);
    }

    private function buildWhereForCollection()
    {
        $where = new Where();

        if (isset($this->collectionFilters['country_id'])) {
            $where->equalTo('country_id', $this->collectionFilters['country_id']);
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