<?php

namespace Test\Delivery\Api;

use Test\Delivery\Api\Data\ShippingDeliveryInterface;

/**
 * @api
 */
interface ShippingDeliveryRepositoryInterface
{

    /**
     * @param ShippingDeliveryInterface $shippingDelivery
     * @return mixed
     */
    public function save(ShippingDeliveryInterface $shippingDelivery);

    /**
     * Get info about time interval by time interval id
     *
     * @param int $modelId
     * @return \Test\Delivery\Api\Data\ShippingDeliveryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($modelId);

    /**
     * Delete time interval
     *
     * @param \Test\Delivery\Api\Data\ShippingDeliveryInterface $shippingDelivery
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ShippingDeliveryInterface $shippingDelivery);

    /**
     * Delete time interval by id
     *
     * @param int $shippingDeliveryId
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($shippingDeliveryId);

    /**
     * Get time interval list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Test\Delivery\Api\Data\ShippingDeliverySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $quoteId
     * @return Data\ShippingDeliveryInterface
     */
    public function getByQuoteId($quoteId);

    /**
     * @param $orderId
     * @return mixed
     */
    public function getByOrderId($orderId);
}
