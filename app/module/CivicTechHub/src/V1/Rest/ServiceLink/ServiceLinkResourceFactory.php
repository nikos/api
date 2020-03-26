<?php
namespace CivicTechHub\V1\Rest\ServiceLink;

use CivicTechHub\V1\Rest\ServiceLink\Model\RepositoryInterface;
use Laminas\ServiceManager\ServiceManager;

class ServiceLinkResourceFactory
{
    public function __invoke(ServiceManager $serviceManager)
    {
        return new ServiceLinkResource(
            $serviceManager->get(RepositoryInterface::class)
        );
    }
}
