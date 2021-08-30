<?php

namespace Test\Delivery\Api\Data;

/**
 * @api
 */
interface TimeIntervalSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Test\Delivery\Api\Data\TimeIntervalInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Test\Delivery\Api\Data\TimeIntervalInterface[] $timeInterval
     * @return $this
     */
    public function setItems(array $timeInterval);
}
