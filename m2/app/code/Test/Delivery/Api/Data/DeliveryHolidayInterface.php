<?php

namespace Test\Delivery\Api\Data;

/**
 * Interface ShippingDeliveryInterface
 * @package Test\Delivery\Api\Data
 */
interface DeliveryHolidayInterface
{

    const HOLIDAY_ID = 'holiday_id';
    const EACH_YEAR = 'each_year';
    const EACH_MONTH = 'each_month';
    const FROM_DAY = 'from_day';
    const FROM_MONTH = 'from_month';
    const FROM_YEAR = 'from_year';
    const TO_DAY = 'to_day';
    const TO_MONTH = 'to_month';
    const TO_YEAR = 'to_year';
    const DESCRIPTION = 'description';

    /**
     * Get Get Holiday Id

     * @return int|null
     */
    public function getHolidayId();

    /**
     * Set  Holiday Id
     *
     * @param string $holiday_id
     * @return $this
     */
    public function setHolidayId($holiday_id);

    /**
     * Get each year

     * @return boolean|null
     */
    public function getEachYear();

    /**
     * Set each year
     *
     * @param boolean $each_year
     * @return $this
     */
    public function setEachYear($each_year);

    /**
     * Get each month

     * @return boolean|null
     */
    public function getEachMonth();

    /**
     * Set each month
     *
     * @param boolean $each_month
     * @return $this
     */
    public function setEachMonth($each_month);

    /**
     * Get from day

     * @return int|null
     */
    public function getFromDay();

    /**
     * Set from day
     * @param int $from_day
     * @return $this
     */
    public function setFromDay($from_day);

    /**
     * Get from month

     * @return int|null
     */
    public function getFromMonth();

    /**
     * Set from month
     * @param int $from_month
     * @return $this
     */
    public function setFromMonth($from_month);

    /**
     * Get from year

     * @return int|null
     */
    public function getFromYear();

    /**
     * Set from year
     * @param int $from_year
     * @return $this
     */
    public function setFromYear($from_year);

    /**
     * Get to day

     * @return int|null
     */
    public function getToDay();

    /**
     * Set to day
     * @param int $to_day
     * @return $this
     */
    public function setToDay($to_day);

    /**
     * Get to month

     * @return int|null
     */
    public function getToMonth();

    /**
     * Set to month
     * @param int $to_month
     * @return $this
     */
    public function setToMonth($to_month);

    /**
     * Get to year

     * @return int|null
     */
    public function getToYear();

    /**
     * Set to month
     * @param int $to_year
     * @return $this
     */
    public function setToYear($to_year);
}
