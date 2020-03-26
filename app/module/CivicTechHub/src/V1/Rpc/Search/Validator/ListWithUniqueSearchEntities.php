<?php

namespace CivicTechHub\V1\Rpc\Search\Validator;


use CivicTechHub\V1\Rpc\Search\SearchController;
use Laminas\Validator\AbstractValidator;

class ListWithUniqueSearchEntities extends AbstractValidator
{
    const ErrorContainsInvalidEntity = 'containsInvalidEntity';
    const ErrorContainsDuplicates = 'containsDuplicates';

    public function __construct($options = null)
    {
        $this->messageTemplates = [
            self::ErrorContainsInvalidEntity => sprintf('List must only contain the following entities: %s.', implode(', ', SearchController::AllEntities)),
            self::ErrorContainsDuplicates => 'List contains duplicate entities.'
        ];

        parent::__construct($options);
    }

    public function isValid($value)
    {
        if (! is_array($value)) {
            throw new \InvalidArgumentException('Value must be an array');
        }

        if ($this->doesListContainInvalidEntities($value)) {
            $this->error(self::ErrorContainsInvalidEntity);
            return false;
        }

        if ($this->doesListContainDuplicates($value)) {
            $this->error(self::ErrorContainsDuplicates);
            return false;
        }

        return true;
    }

    private function doesListContainInvalidEntities(array $entities)
    {
        foreach ($entities as $entity) {
            if (! in_array($entity, SearchController::AllEntities)) {
                return true;
            }
        }

        return false;
    }

    private function doesListContainDuplicates(array $entities)
    {
        $uniqueEntities = array_unique($entities);
        return count($uniqueEntities) != count($entities);
    }
}