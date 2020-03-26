<?php

namespace Application\Validator;


use Laminas\Validator\AbstractValidator;

class NonEmptyListOfUniqueIntegerIds extends AbstractValidator
{
    const ERROR_CONTAINS_NON_INTEGER_ELEMENT = 'containsNonIntegerElement';
    const ERROR_DUPLICATE_ELEMENTS = 'duplicateElements';
    const ERROR_EMPTY = 'empty';
    const ERROR_TOO_FEW_ELEMENTS = 'tooFewElements';

    protected $minRequiredElements;

    protected $messageVariables = [
        'minRequiredElements' => 'minRequiredElements',
    ];

    protected $messageTemplates = [
        self::ERROR_CONTAINS_NON_INTEGER_ELEMENT => 'List contains at least one non integer element.',
        self::ERROR_DUPLICATE_ELEMENTS => 'List contains duplicate ids.',
        self::ERROR_EMPTY => 'List contains no ids.',
        self::ERROR_TOO_FEW_ELEMENTS => 'List must contain at least %minRequiredElements% ids.'
    ];

    public function __construct($options = null)
    {
        if (empty($options['minRequiredElements'])) {
            $options['minRequiredElements'] = 0;
        }

        parent::__construct($options);

        $this->minRequiredElements = $this->getOption('minRequiredElements');
    }


    public function isValid($value)
    {
        if ($this->isListEmptyOrNotAList($value)) {
            $this->error(self::ERROR_EMPTY);
            return false;
        }

        if ($this->doesListContainNonIntegerElements($value)) {
            $this->error(self::ERROR_CONTAINS_NON_INTEGER_ELEMENT);
            return false;
        }

        if ($this->doesListContainDuplicateElements($value)) {
            $this->error(self::ERROR_DUPLICATE_ELEMENTS);
            return false;
        }

        if ($this->doesUniqueListContainLessThanTheMinimumRequiredNumberOfElements($value)) {
            $this->error(self::ERROR_TOO_FEW_ELEMENTS);
            return false;
        }

        return true;
    }

    private function isListEmptyOrNotAList($ids)
    {
        return empty($ids) || ! is_array($ids);
    }

    private function doesListContainNonIntegerElements($ids)
    {
        foreach ($ids as $id) {
            if (! preg_match('/[0-9]+/', $id)) {
                return true;
            }
        }

        return false;
    }

    private function doesListContainDuplicateElements($ids)
    {
        $uniqueIds = array_unique($ids);
        return count($uniqueIds) != count($ids);
    }

    private function doesUniqueListContainLessThanTheMinimumRequiredNumberOfElements($ids)
    {
        return count($ids) < $this->minRequiredElements;
    }
}