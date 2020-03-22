<?php
namespace Covid19CivicTech\V1\Rest\Group\Model;


interface RepositoryInterface
{
    public function fetchAllCountryIds(): array;
}