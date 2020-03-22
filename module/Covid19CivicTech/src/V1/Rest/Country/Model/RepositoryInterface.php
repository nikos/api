<?php
namespace Covid19CivicTech\V1\Rest\Country\Model;

use Covid19CivicTech\V1\Rest\Country\CountryEntity;

interface RepositoryInterface
{
    /**
     * @return CountryEntity[]
     */
    public function fetchAllOrderedByName();

    /**
     * @param int $id
     * @return CountryEntity|null
     */
    public function fetchById(int $id);

    /**
     * @param array $ids
     * @return CountryEntity[]
     */
    public function fetchAllByIds(array $ids);
}