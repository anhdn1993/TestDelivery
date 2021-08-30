<?php

namespace Test\Delivery\Block;

use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class DeliveryInformation
 * @package Test\Delivery\Block
 */
class DeliveryInformation extends Template
{
    /* Field config allow / do not allow using extension Delivery Dates */
    const DELIVERY_GENERAL_ENABLED = 'icdelivery/general/enabled';

    const SALES_ORDER_PRINT = "sales_order_print";
    const SALES_ORDER_PRINTINVOICE = "sales_order_printinvoice";
    const SALES_ORDER_PRINTSHIPMENT = "sales_order_printshipment";

    const DELIVERY_DATE_INCLUDE = 'icdelivery/test_delivery_date/delivery_date_include_into';
    const DELIVERY_TIME_ENABLE = 'icdelivery/test_delivery_time/delivery_time_enabled';
    const DELIVERY_TIME_INCLUDE = 'icdelivery/test_delivery_time/delivery_time_include_into';
    const DELIVERY_COMMENT_ENABLE = 'icdelivery/test_delivery_comment/delivery_comment_enabled';
    const DELIVERY_COMMENT_INCLUDE = 'icdelivery/test_delivery_comment/delivery_comment_include_into';

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ShippingDeliveryRepositoryInterface
     */
    protected $shippingDeliveryRepository;

    /**
     * @var null
     */
    protected $currentOrder = null;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * DeliveryInformation constructor.
     * @param Registry $registry
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentOrder()
    {
        if (!$this->currentOrder) {
            $currentOrder = $this->registry->registry('current_order');
            $this->currentOrder = $this->shippingDeliveryRepository->getByOrderId($currentOrder->getId());
        }
        return $this->currentOrder;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->getCurrentOrder()->getDeliveryDate();
    }

    /**
     * @return mixed
     */
    public function getDeliveryTime()
    {
        return $this->getCurrentOrder()->getDeliveryTime();
    }

    /**
     * @return mixed
     */
    public function getDeliveryComment()
    {
        return $this->getCurrentOrder()->getDeliveryComment();
    }

    /**
     * @return mixed
     */
    public function getDeliveryGeneralEnabled()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_GENERAL_ENABLED, ScopeInterface::SCOPE_WEBSITE);
    }
    /**
     * @return mixed
     */
    public function getDeliveryTimeEnabled()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_TIME_ENABLE, ScopeInterface::SCOPE_WEBSITE);
    }
    /**
     * @return mixed
     */
    public function getDeliveryCommentEnabled()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_COMMENT_ENABLE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return string
     */
    public function getCurrentHandle()
    {
        return $this->request->getFullActionName();
    }

    /**
     * @return string|null
     */
    public function getCurrentTypeEmail()
    {
        $typeEmail = null;
        $handle = $this->getCurrentHandle();
        switch ($handle) {
            case 'sales_order_print':
                $typeEmail = self::SALES_ORDER_PRINT;
                break;
            case 'sales_order_printInvoice':
                $typeEmail = self::SALES_ORDER_PRINTINVOICE;
                break;
            case 'sales_order_printShipment':
                $typeEmail = self::SALES_ORDER_PRINTSHIPMENT;
                break;
            default:
                $typeEmail = null;
                break;
        }
        return $typeEmail;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDateInclude()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_DATE_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return mixed
     */
    public function getDeliveryTimeInclude()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_TIME_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return mixed
     */
    public function getDeliveryCommentInclude()
    {
        return $this->_scopeConfig->getValue(self::DELIVERY_COMMENT_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
    }


    /**
     * @return bool
     */
    public function allowDeliveryDateInclude()
    {
        return in_array($this->getCurrentTypeEmail(), explode(',', $this->getDeliveryDateInclude()))
            ? true : false;
    }

    /**
     * @return bool
     */
    public function allowDeliveryTimeInclude()
    {
        return ($this->getDeliveryTimeEnabled()
            && in_array($this->getCurrentTypeEmail(), explode(',', $this->getDeliveryTimeInclude())))
            ? true : false;
    }

    /**
     * @return bool
     */
    public function allowDeliveryCommentInclude()
    {
        return ($this->getDeliveryCommentEnabled()
            && in_array($this->getCurrentTypeEmail(), explode(',', $this->getDeliveryCommentInclude())))
            ? true : false;
    }
}
