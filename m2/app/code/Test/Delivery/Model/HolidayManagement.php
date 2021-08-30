<?php

namespace Test\Delivery\Model;

/**
 * Class HolidayRepository
 * @package Test\Delivery\Model
 */
class HolidayManagement
{
    protected $storeManager;
    /**
     * @var ResourceModel\Holiday
     */
    protected $holidayResource;
    /**
     * @var \Test\Delivery\Helper\Data
     */
    protected $configHelper;

    /**
     * HolidayManagement constructor.
     * @param ResourceModel\Holiday $holidayResource
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Test\Delivery\Helper\Data $configHelper
     */
    public function __construct(
        \Test\Delivery\Model\ResourceModel\Holiday $holidayResource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Test\Delivery\Helper\Data $configHelper
    ) {
        $this->configHelper = $configHelper;
        $this->holidayResource = $holidayResource;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getHolidayShipping()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $listHoliday = $this->holidayResource->getDataByStore($storeId);
        $maxCountDelivery = $this->configHelper->getDeliveryMaxDate();
        $holidays = [];
        /**
         * @var \Test\Delivery\Api\Data\DeliveryHolidayInterface $item
         */
        for ($i = 0; $i < $maxCountDelivery; $i++) {
            $date = date('yy-m-d', strtotime('+' . $i . ' day'));

            foreach ($listHoliday as $item) {
                $yearFrom = $item->getFromYear();
                $yearTo = $item->getToYear();
                $monthFrom = $item->getFromMonth();
                $monthTo = $item->getToMonth();
                if ($item->getEachYear()) {
                    $yearFrom = date('Y');
                    $yearTo = date('Y');
                }
                if ($item->getEachMonth()) {
                    $monthFrom = date('m');
                    $monthTo = date('m');
                }

                $startDate = $yearFrom . '-' . $monthFrom . '-' . $item->getFromDay();
                $endDate = $yearTo . '-' . $monthTo . '-' . $item->getToDay();
                $inDate = $this->checkInRange($startDate, $endDate, $date);
                if ($inDate) {
                    $holidays[] = $date;
                    break;
                }
            }
        }

        return $holidays;
    }

    private function checkInRange($startDate, $endDate, $dateFromUser)
    {
        // Convert to timestamp
        $start_ts = strtotime($startDate);
        $end_ts = strtotime($endDate);
        $user_ts = strtotime($dateFromUser);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }
}
