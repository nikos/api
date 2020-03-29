<?php

namespace Application\Model;


use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Paginator\Adapter\DbSelect;

abstract class AbstractDatabaseRepository
{
    /**
     * @var \Laminas\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return array
     */
    protected function fetchAllEntities()
    {
        $resultSet = $this->tableGateway->select();

        if ($resultSet->count() < 1) {
            return [];
        }

        return iterator_to_array($resultSet);
    }

    /**
     * @param int $id
     * @return mixed
     */
    protected function fetchEntityByFieldValue(string $field, $value)
    {
        $where = new Where();
        $where->equalTo($field, $value);

        return $this->fetchEntityWithWhere($where);
    }

    /**
     * @param Where $where
     * @return mixed
     */
    protected function fetchEntityWithWhere(Where $where)
    {
        $resultSet = $this->tableGateway->select($where);

        if ($resultSet->count() < 1) {
            return null;
        }

        return $resultSet->current();
    }

    /**
     * @param Select $select
     * @return mixed
     */
    protected function fetchEntityWithSelect(Select $select)
    {
        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() < 1) {
            return null;
        }

        return $resultSet->current();
    }

    protected function getSelect()
    {
        $sql = $this->tableGateway->getSql();
        return $sql->select();
    }

    protected function getPaginatorAdapterForSelect(Select $select)
    {
        $sql = $this->tableGateway->getSql();
        $resultSetPrototype = $this->tableGateway->getResultSetPrototype();

        return new DbSelect($select, $sql, $resultSetPrototype);
    }

    protected function getEnhanceableItemPaginatorAdapterForSelect(Select $select)
    {
        $sql = $this->tableGateway->getSql();
        $resultSetPrototype = $this->tableGateway->getResultSetPrototype();

        return new EnhanceableItemsDbSelectAdapter($select, $sql, $resultSetPrototype);
    }
    /**
     * @param Where $where
     * @return array
     */
    protected function fetchAllEntitiesWithWhere(Where $where)
    {
        $resultSet = $this->tableGateway->select($where);

        if ($resultSet->count() < 1) {
            return [];
        }

        return iterator_to_array($resultSet);
    }

    /**
     * @param Select $select
     * @return array
     */
    protected function fetchAllEntitiesWithSelect(Select $select)
    {
        $resultSet = $this->tableGateway->selectWith($select);

        if ($resultSet->count() < 1) {
            return [];
        }

        return iterator_to_array($resultSet);
    }
}
