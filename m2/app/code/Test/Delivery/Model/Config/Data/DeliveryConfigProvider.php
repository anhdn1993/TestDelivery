<?php
namespace Test\Delivery\Model\Config\Data;

use Magento\Checkout\Model\ConfigProviderInterface;

class DeliveryConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $dataHelper;

    protected $deliveryRepository;

    protected $checkoutSession;

    /**
     * @var \Test\Delivery\Api\DeliveryHolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var \Test\Delivery\Model\HolidayManagement
     */
    protected $holidayManagement;

    /**
     * DeliveryConfigProvider constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Test\Delivery\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Test\Delivery\Api\ShippingDeliveryRepositoryInterface $deliveryRepository
     * @param \Test\Delivery\Api\DeliveryHolidayRepositoryInterface $holidayRepository
     * @param \Test\Delivery\Model\HolidayManagement $holidayManagement
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Test\Delivery\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Test\Delivery\Api\ShippingDeliveryRepositoryInterface $deliveryRepository,
        \Test\Delivery\Api\DeliveryHolidayRepositoryInterface $holidayRepository,
        \Test\Delivery\Model\HolidayManagement $holidayManagement
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->deliveryRepository = $deliveryRepository;
        $this->holidayRepository = $holidayRepository;
        $this->holidayManagement = $holidayManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $deliveryEnable = $this->dataHelper->isDeliveryShipmentEnabled();
        $deliveryDisableDate = $this->dataHelper->getDeliveryDisableDate();
        $holidayDisableDate = $this->getHolidayShipping();
        $maxDate = $this->dataHelper->getDeliveryMaxDate();
        $dateFormat = $this->dataHelper->getDeliveryDateFomrmat();
        $deliveryDateNote = $this->dataHelper->getDeliveryDateFieldNote();
        $dateRequired = $this->dataHelper->getDeliveryDateRequire();
        $selectShowCustomer = $this->dataHelper->getDeliveryDateSelectCustomer();
        $dataCustomerGroup = $this->dataHelper->getDeliveryDateCustomer();
        $selectShippingMethod = $this->dataHelper->getDeliveryDateSelectShipping();
        $dataShippingMethod = $this->dataHelper->getDeliveryDateShippingMethod();

        $deliveryCommentEnable = $this->dataHelper->getDeliveryCommentEnable();
        $deliveryCommentNote = $this->dataHelper->getDeliveryCommentFieldNote();
        $timeRequired = $this->dataHelper->getDeliveryTimeRequire();

        $deliveryTimeEnable = $this->dataHelper->getDeliveryTimeEnable();
        $deliveryTimeNote = $this->dataHelper->getDeliveryTimeFieldNote();
        $commentRequired = $this->dataHelper->getDeliveryCommentRequire();

        $dataTimeInterval = $this->getDataTimInterval();

        $deliveryDate = '';
        $deliveryTime = '';
        $deliveryComment = '';
        $quoteId = $this->checkoutSession->getQuote()->getEntityId();
        $delivery = $this->deliveryRepository->getByQuoteId($quoteId);
        if ($delivery) {
            $deliveryDate = $delivery->getDeliveryDate();
            $deliveryTime = $delivery->getDeliveryTime();
            $deliveryComment = $delivery->getDeliveryComment();
        }

        $config = [
            'shipping' => [
                'delivery_date_data' =>  [
                    'delivery_date_data_date' => $deliveryDate,
                    'delivery_date_data_time' => $deliveryTime,
                    'delivery_date_data_comment' => $deliveryComment
                ],
                'delivery_enable'=> $deliveryEnable,
                'delivery_disable_date'=> $deliveryDisableDate,
                'holiday_disable_date'=> $holidayDisableDate,
                'max_date'=> $maxDate,

                'delivery_date' => [
                    'data_time_interval' => $dataTimeInterval,
                    'date_format'=> $dateFormat,
                    'delivery_date_note'=> $deliveryDateNote,
                    'delivery_date_required'=> $dateRequired,
                    'select_show_customer'=> $selectShowCustomer,
                    'data_customer_group'=> $dataCustomerGroup,
                    'select_shipping_method'=> $selectShippingMethod,
                    'data_shipping_method'=> $dataShippingMethod,
                ],
                'delivery_time' => [
                    'delivery_time_enable'=> $deliveryTimeEnable,
                    'delivery_time_note'=> $deliveryCommentNote,
                    'delivery_time_required'=> $timeRequired,
                ],
                'delivery_comment' => [
                    'delivery_comment_enable'=> $deliveryCommentEnable,
                    'delivery_comment_note'=> $deliveryTimeNote,
                    'delivery_comment_required'=> $commentRequired,
                ]
            ]
        ];

        return $config;
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDataTimInterval()
    {
        $arrResult = $this->dataHelper->getDataTimInterval();

        return $arrResult;
    }

    public function getHolidayShipping()
    {
        return $this->holidayManagement->getHolidayShipping();
    }
}
