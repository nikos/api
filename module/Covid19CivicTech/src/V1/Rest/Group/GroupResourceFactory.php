<?php
namespace Covid19CivicTech\V1\Rest\Group;

class GroupResourceFactory
{
    public function __invoke($services)
    {
        return new GroupResource();
    }
}
