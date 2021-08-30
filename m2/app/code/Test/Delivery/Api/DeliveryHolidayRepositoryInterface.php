<?php

namespace Test\Delivery\Api;

use Test\Delivery\Api\Data\DeliveryHolidayInterface;

/**
 * @api
 */
interface DeliveryHolidayRepositoryInterface
{

    /**
     * @param DeliveryHolidayInterface $holiday
     * @return mixed
     */
    public function save(DeliveryHolidayInterface $holiday);

    /**
     * Get info about holiday by holiday id
     *
     * @param int $modelId
     * @return \Test\Delivery\Api\Data\DeliveryHolidayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($modelId);

    /**
     * Delete holiday
     *
     * @param \Test\Delivery\Api\Data\DeliveryHolidayInterface $holiday
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(DeliveryHolidayInterface $holiday);

    /**
     * Delete holiday by id
     *
     * @param int $holidayId
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($holidayId);

    /**
     * Get holiday list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Test\Delivery\Api\Data\DeliveryHolidaySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
