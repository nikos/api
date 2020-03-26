<?php
namespace CivicTechHub\V1\Rest\Topic;

use CivicTechHub\V1\Rest\Topic\Model\RepositoryInterface;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;

class TopicResource extends AbstractResourceListener
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return $this->repository->fetchById($id);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return $this->repository->fetchAll();
    }
}
