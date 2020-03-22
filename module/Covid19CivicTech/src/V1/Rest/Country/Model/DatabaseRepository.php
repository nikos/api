<?php


namespace Covid19CivicTech\V1\Rest\Country\Model;


use Application\Model\AbstractDatabaseRepository;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class DatabaseRepository extends AbstractDatabaseRepository implements RepositoryInterface
{
    /**
     * @var \Covid19CivicTech\V1\Rest\Group\Model\RepositoryInterface
     */
    private $groupRepository;

    public function __construct(TableGateway $tableGateway, \Covid19CivicTech\V1\Rest\Group\Model\RepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;

        parent::__construct($tableGateway);
    }

    public function fetchAllWithGroupsOrderedByName()
    {
        $countryIds = $this->groupRepository->fetchAllCountryIds();
        $where = new Where();
        $where->in('id', $countryIds);

        $select = $this->getSelect();
        $select->where($where);
        $select->order('name');

        return $this->fetchAllEntitiesWithSelect($select);
    }

    public function fetchById(int $id)
    {
        return $this->fetchEntityByFieldValue('id', $id);
    }

    public function fetchAllByIds(array $ids)
    {
        // TODO: Implement fetchAllByIds() method.
    }


}