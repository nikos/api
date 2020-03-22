<?php
namespace Covid19CivicTech\V1\Rest\Country;

use Covid19CivicTech\V1\Rest\Country\Model\RepositoryInterface;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;

class CountryResource extends AbstractResourceListener
{
    /**
     * @var RepositoryInterface
     */
    private $countryRepository;
    /**
     * @var \Covid19CivicTech\V1\Rest\Group\Model\RepositoryInterface
     */
    private $groupRepository;

    public function __construct(
        RepositoryInterface $countryRepository,
        \Covid19CivicTech\V1\Rest\Group\Model\RepositoryInterface $groupRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return $this->countryRepository->fetchById($id);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return $this->countryRepository->fetchAllByIdsOrderedByName(
            $this->groupRepository->fetchAllCountryIds()
        );
    }
}
