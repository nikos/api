<?php
namespace Covid19CivicTech\V1\Rest\ServiceLink;

class ServiceLinkResourceFactory
{
    public function __invoke($services)
    {
        return new ServiceLinkResource();
    }
}
