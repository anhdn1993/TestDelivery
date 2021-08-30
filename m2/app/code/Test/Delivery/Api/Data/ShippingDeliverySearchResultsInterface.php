<?php

namespace Test\Delivery\Api\Data;

/**
 * @api
 */
interface ShippingDeliverySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Test\Delivery\Api\Data\ShippingDeliveryInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Test\Delivery\Api\Data\ShippingDeliveryInterface[] $shippingDelivery
     * @return $this
     */
    public function setItems(array $shippingDelivery);
}
