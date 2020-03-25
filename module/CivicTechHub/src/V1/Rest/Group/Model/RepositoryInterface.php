<?php
namespace CivicTechHub\V1\Rest\Group\Model;


use CivicTechHub\V1\Rest\Group\GroupCollection;
use CivicTechHub\V1\Rest\Group\GroupEntity;

interface RepositoryInterface
{
    public function fetchAllCountryIds(): array;

    /**
     * @return GroupEntity|null
     */
    public function fetchById(int $id);

    public function addFilterByCountryForCollection(int $countryId);
    public function addFilterByTopicsForCollection(array $topicIds);

    public function getCollection(): GroupCollection;
}