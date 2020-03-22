<?php
namespace Covid19CivicTech\V1\Rest\Topic;

class TopicResourceFactory
{
    public function __invoke($services)
    {
        return new TopicResource();
    }
}
