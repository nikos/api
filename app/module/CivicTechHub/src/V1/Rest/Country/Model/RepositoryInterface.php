<?php
namespace CivicTechHub\V1\Rest\Country\Model;

use CivicTechHub\V1\Rest\Country\CountryEntity;

interface RepositoryInterface
{
    /**
     * @param int $id
     * @return CountryEntity|null
     */
    public function fetchById(int $id);

    /**
     * @param array $ids
     * @return CountryEntity[]
     */
    public function fetchAllByIdsOrderedByName(array $ids);

    /**
     * @param string $searchphrase
     * @return CountryEntity[]
     */
    public function fetchForSearchphrase(string $searchphrase, int $limit): array;
}