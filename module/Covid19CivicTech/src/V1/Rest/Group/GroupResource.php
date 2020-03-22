<?php
namespace Covid19CivicTech\V1\Rest\Group;

use Covid19CivicTech\V1\Rest\Country\CountryEntity;
use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkCollection;
use Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkEntity;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;

class GroupResource extends AbstractResourceListener
{
    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return null;
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }
}
