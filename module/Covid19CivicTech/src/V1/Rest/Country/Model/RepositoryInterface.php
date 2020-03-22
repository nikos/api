<?php
namespace Covid19CivicTech\V1\Rest\Country\Model;

use Covid19CivicTech\V1\Rest\Country\CountryEntity;

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
}