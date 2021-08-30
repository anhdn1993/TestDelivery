<?php

namespace Test\Delivery\Api\Data;

/**
 * Interface TimeIntervalInterface
 * @package Test\Delivery\Api\Data
 */
interface TimeIntervalInterface
{

    const FROM = 'from';
    const TO = 'to';
    const POSITION = 'position';
    const STORE_ID = 'store_ids';

    /**
     * Get Get Time Interval Id

     * @return int|null
     */
    public function getTimeIntervalId();

    /**
     * Set Time Interval Id
     *
     * @param string $time_interval_id
     * @return $this
     */
    public function setTimeIntervalId($time_interval_id);

    /**
     * Get From
     * @return int|null
     */
    public function getFrom();

    /**
     * Set From
     *
     * @param string $from
     * @return $this
     */
    public function setFrom($from);

    /**
     * Get To
     * @return int|null
     */
    public function getTo();

    /**
     * Set To
     *
     * @param string $to
     * @return $this
     */
    public function setTo($to);

    /**
     * Get Position
     * @return int|null
     */
    public function getPosition();

    /**
     * Set Position
     *
     * @param string $position
     * @return $this
     */
    public function setPosition($position);
//    /**
//     * Get Store id
//     * @return array|null
//     */
//    public function getStoreIds();
//
//    /**
//     * Set Position
//     *
//     * @param array $store
//     * @return $this
//     */
//    public function setStoreIds($store);
}
