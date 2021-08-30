<?php

namespace Test\Delivery\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Test\Delivery\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DELIVERY_SHIPMENT_ENABLED = 'icdelivery/general/enabled';
    const DELIVERY_DISABLE_DATE_DELIVERY = 'icdelivery/general/disable_delivery_on';
    const DELIVERY_MAX_DATE = 'icdelivery/general/max_delivery_interval';

    const DELIVERY_DATE_FORMAT = 'icdelivery/test_delivery_date/delivery_date_format';
    const DELIVERY_DATE_REQUIRE = 'icdelivery/test_delivery_date/delivery_date_is_required';
    const DELIVERY_DATE_INCLUDE = 'icdelivery/test_delivery_date/delivery_date_include_into';
    const DELIVERY_DATE_DISPLAYON = 'icdelivery/test_delivery_date/delivery_date_display_on';
    const DELIVERY_DATE_FILED_NOTE = 'icdelivery/test_delivery_date/delivery_date_filed_note';
    const DELIVERY_DATE_SELECT_CUSTOMER_GROUP = 'icdelivery/test_delivery_date/delivery_date_select_customer_group';
    const DELIVERY_DATE_CUSTOMER_GROUP = 'icdelivery/test_delivery_date/delivery_date_customer_group';
    const DELIVERY_DATE_SELECT_SHIPPING_METHOD = 'icdelivery/test_delivery_date/delivery_date_select_shipping_method';
    const DELIVERY_DATE_SHIPPING_METHOD = 'icdelivery/test_delivery_date/delivery_date_shipping_method';

    const DELIVERY_TIME_ENABLE = 'icdelivery/test_delivery_time/delivery_time_enabled';
    const DELIVERY_TIME_FIELD_NOTE = 'icdelivery/test_delivery_time/delivery_time_filed_note';
    const DELIVERY_TIME_DISPLAYON = 'icdelivery/test_delivery_time/delivery_time_display_on';
    const DELIVERY_TIME_INCLUDE = 'icdelivery/test_delivery_time/delivery_time_include_into';
    const DELIVERY_TIME_REQUIRE = 'icdelivery/test_delivery_time/delivery_time_is_required';

    const DELIVERY_COMMENT_ENABLE = 'icdelivery/test_delivery_comment/delivery_comment_enabled';
    const DELIVERY_COMMENT_FIELD_NOTE = 'icdelivery/test_delivery_comment/delivery_comment_filed_note';
    const DELIVERY_COMMENT_DISPLAYON = 'icdelivery/test_delivery_comment/delivery_comment_display_on';
    const DELIVERY_COMMENT_INCLUDE = 'icdelivery/test_delivery_comment/delivery_comment_include_into';
    const DELIVERY_COMMENT_REQUIRE = 'icdelivery/test_delivery_comment/delivery_comment_is_required';

    const DELIVERY_GENERAL_ENABLED = "icdelivery/general/enabled";
    const SALES_ORDER_VIEW = "sales_order_view";
    const SALES_ORDER_INVOICE_VIEW = "sales_order_invoice_view";
    const ADMINHTML_ORDER_SHIPMENT_VIEW = "adminhtml_order_shipment_view";

    const DELIVERY_DATE = "date";
    const DELIVERY_COMMENT = "comment";
    const DELIVERY_TIME = "time";

    protected $timeInterval;

    private $_storeManager;
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Test\Delivery\Model\ResourceModel\TimeInterval $timeInterval
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Test\Delivery\Model\ResourceModel\TimeInterval $timeInterval
    ) {
        $this->_storeManager = $storeManager;
        $this->timeInterval = $timeInterval;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function getDeliveryTimeEnable()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_TIME_ENABLE);
    }

    /**
     * @return bool
     */
    public function getDeliveryCommentEnable()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_COMMENT_ENABLE);
    }

    /**
     * @return bool
     */
    public function getDeliveryDateFieldNote()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_FILED_NOTE);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryTimeFieldNote()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_TIME_FIELD_NOTE);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryCommentFieldNote()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_COMMENT_FIELD_NOTE);
        return $data;
    }

    /**
    * @return bool
    */
    public function getDeliveryDateShippingMethod()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_SHIPPING_METHOD);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryDateSelectShipping()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_DATE_SELECT_SHIPPING_METHOD);
    }

    /**
    * @return bool
    */
    public function getDeliveryDateSelectCustomer()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_DATE_SELECT_CUSTOMER_GROUP);
    }
    /**
     * @return bool
     */
    public function getDeliveryDateCustomer()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_CUSTOMER_GROUP);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryDateInclude()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_INCLUDE);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryTimeInclude()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_TIME_INCLUDE);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryCommentInclude()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_COMMENT_INCLUDE);
        return $data;
    }

    /**
      * @return bool
      */
    public function getDeliveryDateDisplayOn()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_DISPLAYON);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryTimeDisplayOn()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_TIME_DISPLAYON);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryCommentDisplayOn()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_COMMENT_DISPLAYON);
        return $data;
    }

    /**
       * @return bool
       */
    public function getDeliveryDateRequire()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_DATE_REQUIRE);
    }

    /**
       * @return bool
       */
    public function getDeliveryTimeRequire()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_TIME_REQUIRE);
    }

    /**
       * @return bool
       */
    public function getDeliveryCommentRequire()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_COMMENT_REQUIRE);
    }

    /**
    * @return bool
    */
    public function getDeliveryDateFomrmat()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DATE_FORMAT);
        return $data;
    }

    /**
     * @return bool
     */
    public function isDeliveryShipmentEnabled()
    {
        return (bool)$this->scopeConfig->getValue(self::DELIVERY_SHIPMENT_ENABLED);
    }

    /**
     * @return bool
     */
    public function getDeliveryDisableDate()
    {
        $data =  $this->scopeConfig->getValue(self::DELIVERY_DISABLE_DATE_DELIVERY);
        return $data;
    }

    /**
     * @return bool
     */
    public function getDeliveryMaxDate()
    {
        $maxDate =  $this->scopeConfig->getValue(self::DELIVERY_MAX_DATE);
        return $maxDate;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDataTimInterval()
    {
        $arrResult = [];
        $storeId = $this->_storeManager->getStore()->getId();
        $dataTimeInterval = $this->timeInterval->getDataByStore($storeId);
        $dataTimeInterval = array_unique($dataTimeInterval, 0);

        foreach ($dataTimeInterval as $timeInterval) {
            array_push($arrResult, $timeInterval["from"] . '-' . $timeInterval["to"]);
        }

        return $arrResult;
    }



    /**
     * @param $parameter
     * @return bool
     */
    public function allowDisplayOnBackend($parameter)
    {
        $fullActionName = $this->_request->getFullActionName();
        $displayOnNeedToCheck = null;
        $pathConfig = null;
        switch ($fullActionName) {
            case 'sales_order_view':
                $displayOnNeedToCheck = self::SALES_ORDER_VIEW;
                break;
            case 'sales_order_invoice_view':
                $displayOnNeedToCheck = self::SALES_ORDER_INVOICE_VIEW;
                break;
            case 'adminhtml_order_shipment_view':
                $displayOnNeedToCheck = self::ADMINHTML_ORDER_SHIPMENT_VIEW;
                break;
            default:
                $displayOnNeedToCheck = null;
                break;
        }
        switch ($parameter) {
            case self::DELIVERY_DATE:
                $pathConfig = self::DELIVERY_DATE_DISPLAYON;
                break;
            case self::DELIVERY_TIME:
                $pathConfig = self::DELIVERY_TIME_DISPLAYON;
                break;
            case self::DELIVERY_COMMENT:
                $pathConfig = self::DELIVERY_COMMENT_DISPLAYON;
                break;
            default:
                $pathConfig = null;
                break;
        }

        $deliveryDatesDisplayOn = $this->scopeConfig->getValue(
            $pathConfig,
            ScopeInterface::SCOPE_WEBSITE
        );
        $deliveryGeneralEnabled = $this->scopeConfig->getValue(
            self::DELIVERY_GENERAL_ENABLED,
            ScopeInterface::SCOPE_WEBSITE
        );
        return ($deliveryGeneralEnabled && in_array($displayOnNeedToCheck, explode(',', $deliveryDatesDisplayOn)))
            ? true : false;
    }
}
