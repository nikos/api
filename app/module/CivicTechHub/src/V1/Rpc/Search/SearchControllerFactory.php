<?php
namespace CivicTechHub\V1\Rpc\Search;

use CivicTechHub\V1\Rest\Country\Model\RepositoryInterface as CountryRepositoryInterface;
use CivicTechHub\V1\Rest\Group\Model\RepositoryInterface as GroupRepositoryInterface;
use CivicTechHub\V1\Rest\Topic\Model\RepositoryInterface as TopicRepositoryInterface;
use Laminas\ServiceManager\ServiceManager;

class SearchControllerFactory
{
    public function __invoke(ServiceManager $serviceManager)
    {
        return new SearchController(
            $serviceManager->get(CountryRepositoryInterface::class),
            $serviceManager->get(GroupRepositoryInterface::class),
            $serviceManager->get(TopicRepositoryInterface::class)
        );
    }
}
