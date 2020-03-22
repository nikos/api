<?php

namespace Application\Model;

use Laminas\Paginator\Adapter\DbSelect;

class EnhanceableItemsDbSelectAdapter extends DbSelect
{
    /**
     * @var callable|null
     */
    private $beforeEnhanceItemsCallback = null;

    /**
     * @var callable|null
     */
    private $enhanceItemFunction = null;

    public function getItems($offset, $itemCountPerPage)
    {
        $items = parent::getItems($offset, $itemCountPerPage);

        if (! empty($this->beforeEnhanceItemsCallback)) {
            ($this->beforeEnhanceItemsCallback)($items);
        }

        $enhancedItems = [];
        foreach ($items as $item) {
            $enhancedItems[] = $this->enhanceItem($item);
        }

        return $enhancedItems;
    }

    public function setBeforeEnhanceItemsCallback(callable $beforeEnahnceItemsCallback)
    {
        $this->beforeEnhanceItemsCallback = $beforeEnahnceItemsCallback;
    }

    public function setEnhanceItemFunction(callable $enhanceItemFunction)
    {
        $this->enhanceItemFunction = $enhanceItemFunction;
    }

    private function enhanceItem($item)
    {
        if (empty($this->enhanceItemFunction)) {
            return $item;
        }

        return ($this->enhanceItemFunction)($item);
    }
}
