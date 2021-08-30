<?php

namespace Test\Delivery\Model;

use Test\Delivery\Api\Data\DeliveryHolidayInterface;
use Test\Delivery\Model\ResourceModel\Holiday as ResourceHoliday;
use Magento\Framework\Model\AbstractModel;

class Holiday extends AbstractModel implements DeliveryHolidayInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'test_delivery_holiday';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'test_delivery_holiday';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_delivery_holiday';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceHoliday::class);
    }

    /**
     * Get Get Holiday Id
     * @return int|null
     */
    public function getHolidayId()
    {
        return $this->_getData(self::HOLIDAY_ID);
    }

    /**
     * Set  Holiday Id
     *
     * @param string $holiday_id
     * @return $this
     */
    public function setHolidayId($holiday_id)
    {
        return $this->setData(self::HOLIDAY_ID, $holiday_id);
    }

    /**
     * Get each year
     * @return boolean|null
     */
    public function getEachYear()
    {
        return $this->_getData(self::EACH_YEAR);
    }

    /**
     * Set each year
     *
     * @param boolean $each_year
     * @return $this
     */
    public function setEachYear($each_year)
    {
        return $this->setData(self::EACH_YEAR, $each_year);
    }

    /**
     * Get each month
     * @return boolean|null
     */
    public function getEachMonth()
    {
        return $this->_getData(self::EACH_MONTH);
    }

    /**
     * Set each month
     *
     * @param boolean $each_month
     * @return $this
     */
    public function setEachMonth($each_month)
    {
        return $this->setData(self::EACH_MONTH, $each_month);
    }

    /**
     * Get from day
     * @return int|null
     */
    public function getFromDay()
    {
        return $this->_getData(self::FROM_DAY);
    }

    /**
     * Set from day
     * @param int $from_day
     * @return $this
     */
    public function setFromDay($from_day)
    {
        return $this->setData(self::FROM_DAY, $from_day);
    }

    /**
     * Get from month
     * @return int|null
     */
    public function getFromMonth()
    {
        return $this->_getData(self::FROM_MONTH);
    }

    /**
     * Set from month
     * @param int $from_month
     * @return $this
     */
    public function setFromMonth($from_month)
    {
        return $this->setData(self::FROM_MONTH, $from_month);
    }

    /**
     * Get to day
     * @return int|null
     */
    public function getToDay()
    {
        return $this->_getData(self::TO_DAY);
    }

    /**
     * Set to day
     * @param int $to_day
     * @return $this
     */
    public function setToDay($to_day)
    {
        return $this->setData(self::TO_DAY, $to_day);
    }

    /**
     * Get from year
     * @return int|null
     */
    public function getFromYear()
    {
        return $this->_getData(self::FROM_YEAR);
    }

    /**
     * Set from year
     * @param int $from_year
     * @return $this
     */
    public function setFromYear($from_year)
    {
        return $this->setData(self::FROM_YEAR, $from_year);
    }

    /**
     * Get to month
     * @return int|null
     */
    public function getToMonth()
    {
        return $this->_getData(self::TO_MONTH);
    }

    /**
     * Set to month
     * @param int $to_month
     * @return $this
     */
    public function setToMonth($to_month)
    {
        return $this->setData(self::TO_MONTH, $to_month);
    }

    /**
     * Get to year
     * @return int|null
     */
    public function getToYear()
    {
        return $this->_getData(self::TO_YEAR);
    }

    /**
     * Set to month
     * @param int $to_year
     * @return $this
     */
    public function setToYear($to_year)
    {
        return $this->setData(self::TO_YEAR, $to_year);
    }
}
