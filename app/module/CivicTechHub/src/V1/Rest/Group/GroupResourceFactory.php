<?php
namespace CivicTechHub\V1\Rest\Group;

use CivicTechHub\V1\Rest\Group\Model\RepositoryInterface;
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
