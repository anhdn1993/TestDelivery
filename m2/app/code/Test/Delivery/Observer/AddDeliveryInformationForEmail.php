<?php

namespace Test\Delivery\Observer;

use Test\Delivery\Api\ShippingDeliveryRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class AddDeliveryInformationForEmail
 * @package Test\Delivery\Observer
 */
class AddDeliveryInformationForEmail implements ObserverInterface
{
    /* Field config allow / do not allow using extension Delivery Dates */
    const DELIVERY_GENERAL_ENABLED = 'icdelivery/general/enabled';

    /* List emails need to include information delivery dates */
    const SALES_EMAIL_ORDER_ITEMS = "sales_email_order_items";
    const SALES_EMAIL_ORDER_INVOICE_ITEMS = "sales_email_order_invoice_items";
    const SALES_EMAIL_ORDER_SHIPMENT_ITEMS = 'sales_email_order_shipment_items';

    const DELIVERY_DATE_INCLUDE = 'icdelivery/test_delivery_date/delivery_date_include_into';
    const DELIVERY_TIME_ENABLE = 'icdelivery/test_delivery_time/delivery_time_enabled';
    const DELIVERY_TIME_INCLUDE = 'icdelivery/test_delivery_time/delivery_time_include_into';
    const DELIVERY_COMMENT_ENABLE = 'icdelivery/test_delivery_comment/delivery_comment_enabled';
    const DELIVERY_COMMENT_INCLUDE = 'icdelivery/test_delivery_comment/delivery_comment_include_into';

    /**
     * @var ShippingDeliveryRepositoryInterface
     */
    protected $shippingDeliveryRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var Template
     */
    protected $template;

    /**
     * DeliveryInformationNewOrderEmail constructor.
     * @param ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Http $request
     * @param Template $template
     */
    public function __construct(
        ShippingDeliveryRepositoryInterface $shippingDeliveryRepository,
        ScopeConfigInterface $scopeConfig,
        Http $request,
        Template $template
    ) {
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
        $this->template = $template;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $deliveryGeneralEnabled = $this->scopeConfig->getValue(self::DELIVERY_GENERAL_ENABLED, ScopeInterface::SCOPE_WEBSITE);
        $arrayAllowElement = array('items');
        $arrayAllowHandle = array('sales_order_email', 'sales_order_invoice_email', 'adminhtml_order_shipment_email', '__');
        $handle = $this->request->getFullActionName();
        $element = $observer->getElementName();
        if ($deliveryGeneralEnabled && in_array($element, $arrayAllowElement) && in_array($handle, $arrayAllowHandle)) {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($element);
            $order = $orderShippingViewBlock->getOrder();
            $deliveryInfo = $this->shippingDeliveryRepository->getByOrderId($order->getId());

            if ($deliveryInfo) {
                $allowDeliveryDateInclude = false;
                $allowDeliveryTimeInclude = false;
                $allowDeliveryCommentInclude = false;
                $typeEmail = null;
                switch ($handle) {
                    case '__':
                    case 'sales_order_email':
                        $typeEmail = self::SALES_EMAIL_ORDER_ITEMS;
                        break;
                    case 'sales_order_invoice_email':
                        $typeEmail = self::SALES_EMAIL_ORDER_INVOICE_ITEMS;
                        break;
                    case 'adminhtml_order_shipment_email':
                        $typeEmail = self::SALES_EMAIL_ORDER_SHIPMENT_ITEMS;
                        break;
                    default:
                        $typeEmail = null;
                        break;
                }

                /* Get value of configuration Delivery Dates with scope Website */
                $deliveryDateInclude = $this->scopeConfig->getValue(self::DELIVERY_DATE_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
                $deliveryTimeInclude = $this->scopeConfig->getValue(self::DELIVERY_TIME_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
                $deliveryCommentInclude = $this->scopeConfig->getValue(self::DELIVERY_COMMENT_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
                $deliveryTimeEnabled = $this->scopeConfig->getValue(self::DELIVERY_TIME_ENABLE, ScopeInterface::SCOPE_WEBSITE);
                $deliveryCommentEnabled = $this->scopeConfig->getValue(self::DELIVERY_COMMENT_ENABLE, ScopeInterface::SCOPE_WEBSITE);

                if (in_array($typeEmail, explode(',', $deliveryDateInclude))) {
                    $allowDeliveryDateInclude = true;
                }
                if ($deliveryTimeEnabled && in_array($typeEmail, explode(',', $deliveryTimeInclude))) {
                    $allowDeliveryTimeInclude = true;
                }
                if ($deliveryCommentEnabled && in_array($typeEmail, explode(',', $deliveryCommentInclude))) {
                    $allowDeliveryCommentInclude = true;
                }

                /* SetData into block DeliveryInformation */
                $deliveryInformationBlock = $this->template;
                $deliveryInformationBlock->setData('allowPrint', ($allowDeliveryDateInclude || $allowDeliveryTimeInclude || $allowDeliveryCommentInclude) ? true : false);
                $deliveryInformationBlock->setData('allowDeliveryDateInclude', $allowDeliveryDateInclude);
                $deliveryInformationBlock->setData('allowDeliveryTimeInclude', $allowDeliveryTimeInclude);
                $deliveryInformationBlock->setData('allowDeliveryCommentInclude', $allowDeliveryCommentInclude);
                $deliveryInformationBlock->setData('deliveryDate', $deliveryInfo->getdata('delivery_date'));
                $deliveryInformationBlock->setData('deliveryTime', $deliveryInfo->getdata('delivery_time'));
                $deliveryInformationBlock->setData('deliveryComment', $deliveryInfo->getdata('delivery_comment'));
                $deliveryInformationBlock->setHandle($handle);
                $deliveryInformationBlock->setModuleName($orderShippingViewBlock->getModuleName());

                $deliveryInformationBlock->setTemplate('Test_Delivery::email/delivery_information.phtml');
                $html = $deliveryInformationBlock->toHtml() . $observer->getTransport()->getOutput();
                $observer->getTransport()->setOutput($html);
            }
        }
    }
}
