<?php

namespace Test\Delivery\Api\Data;

/**
 * @api
 */
interface DeliveryHolidaySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Test\Delivery\Api\Data\DeliveryHolidayInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Test\Delivery\Api\Data\DeliveryHolidayInterface[] $shippingDeliveryHoliday
     * @return $this
     */
    public function setItems(array $shippingDeliveryHoliday);
}
