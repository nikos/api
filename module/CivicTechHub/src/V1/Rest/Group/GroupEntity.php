<?php
namespace CivicTechHub\V1\Rest\Group;

use CivicTechHub\V1\Rest\Country\CountryEntity;
use CivicTechHub\V1\Rest\ServiceLink\ServiceLinkEntity;
use CivicTechHub\V1\Rest\Topic\TopicEntity;

class GroupEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $countryId;

    /**
     * @var CountryEntity|null
     */
    public $country = null;

    /**
     * @var ServiceLinkEntity[]
     */
    public $serviceLinks = [];

    /**
     * @var TopicEntity[]
     */
    public $topics = [];
}
