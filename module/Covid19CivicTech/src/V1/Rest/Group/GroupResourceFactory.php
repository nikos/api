<?php
namespace Covid19CivicTech\V1\Rest\Group;

use Covid19CivicTech\V1\Rest\Group\Model\RepositoryInterface;
use Laminas\ServiceManager\ServiceManager;

class GroupResourceFactory
{
    public function __invoke(ServiceManager $serviceManager)
    {
        return new GroupResource(
            $serviceManager->get(RepositoryInterface::class)
        );
    }
}
