<?php
namespace Covid19CivicTech\V1\Rest\Topic;

use Covid19CivicTech\V1\Rest\Topic\Model\RepositoryInterface;
use Laminas\ServiceManager\ServiceManager;

class TopicResourceFactory
{
    public function __invoke(ServiceManager $serviceManager)
    {
        return new TopicResource(
            $serviceManager->get(RepositoryInterface::class)
        );
    }
}
