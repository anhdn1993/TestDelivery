<?php

namespace Test\Delivery\Api;

use Test\Delivery\Api\Data\TimeIntervalInterface;

/**
 * @api
 */
interface TimeIntervalRepositoryInterface
{

    /**
     * @param TimeIntervalInterface $timeInterval
     * @return mixed
     */
    public function save(TimeIntervalInterface $timeInterval);

    /**
     * Get info about time interval by time interval id
     *
     * @param int $modelId
     * @return \Test\Delivery\Api\Data\TimeIntervalInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($modelId);

    /**
     * Delete time interval
     *
     * @param \Test\Delivery\Api\Data\TimeIntervalInterface $timeInterval
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(TimeIntervalInterface $timeInterval);

    /**
     * Delete time interval by id
     *
     * @param int $timeIntervalId
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($timeIntervalId);

    /**
     * Get time interval list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Test\Delivery\Api\Data\TimeIntervalSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
