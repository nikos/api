<?php
namespace CivicTechHub\V1\Rest\Country;

class CountryEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $iso3166Code;

    /**
     * @var int|null
     */
    public $countGroups;

}
