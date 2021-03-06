<?php
namespace CivicTechHub\V1\Rest\Country;

use CivicTechHub\V1\Rest\Country\Model\RepositoryInterface;
use Laminas\ServiceManager\ServiceManager;

class CountryResourceFactory
{
    public function __invoke(ServiceManager $serviceManager)
    {
        return new CountryResource(
            $serviceManager->get(RepositoryInterface::class),
            $serviceManager->get(\CivicTechHub\V1\Rest\Group\Model\RepositoryInterface::class)
        );
    }
}
