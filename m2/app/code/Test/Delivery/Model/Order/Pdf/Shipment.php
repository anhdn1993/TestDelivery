<?php

namespace Test\Delivery\Model\Order\Pdf;

use IntlDateFormatter;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Store\Model\ScopeInterface;
use Zend_Pdf_Color_GrayScale;
use Zend_Pdf_Color_Rgb;
use Zend_Pdf_Exception;
use Zend_Pdf_Page;

/**
 * Class Shipment
 * @package Test\Delivery\Model\Order\Pdf
 */
class Shipment extends \Magento\Sales\Model\Order\Pdf\Shipment
{
    const SALES_ORDER_PRINTSHIPMENT = "sales_order_printshipment";

    /* Field config allow / do not allow using extension Delivery Dates */
    const DELIVERY_GENERAL_ENABLED = 'icdelivery/general/enabled';

    const DELIVERY_DATE_INCLUDE = 'icdelivery/test_delivery_date/delivery_date_include_into';
    const DELIVERY_TIME_ENABLE = 'icdelivery/test_delivery_time/delivery_time_enabled';
    const DELIVERY_TIME_INCLUDE = 'icdelivery/test_delivery_time/delivery_time_include_into';
    const DELIVERY_COMMENT_ENABLE = 'icdelivery/test_delivery_comment/delivery_comment_enabled';
    const DELIVERY_COMMENT_INCLUDE = 'icdelivery/test_delivery_comment/delivery_comment_include_into';

    /**
     * @var \Test\Delivery\Api\ShippingDeliveryRepositoryInterface
     */
    protected $shippingDeliveryRepository;

    /**
     * Shipment constructor.
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param Config $pdfConfig
     * @param Order\Pdf\Total\Factory $pdfTotalFactory
     * @param Order\Pdf\ItemsFactory $pdfItemsFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param Order\Address\Renderer $addressRenderer
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Test\Delivery\Api\ShippingDeliveryRepositoryInterface $shippingDeliveryRepository
     * @param array $data
     */
    public function __construct(\Magento\Payment\Helper\Data $paymentData, \Magento\Framework\Stdlib\StringUtils $string, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Filesystem $filesystem, Config $pdfConfig, \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory, \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Sales\Model\Order\Address\Renderer $addressRenderer, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Store\Model\App\Emulation $appEmulation, \Test\Delivery\Api\ShippingDeliveryRepositoryInterface $shippingDeliveryRepository, array $data = [])
    {
        parent::__construct($paymentData, $string, $scopeConfig, $filesystem, $pdfConfig, $pdfTotalFactory, $pdfItemsFactory, $localeDate, $inlineTranslation, $addressRenderer, $storeManager, $appEmulation, $data);
        $this->shippingDeliveryRepository = $shippingDeliveryRepository;
    }

    /**
     * @param Zend_Pdf_Page $page
     * @param Order $obj
     * @param bool $putOrderId
     * @throws Zend_Pdf_Exception
     */
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        if ($obj instanceof Order) {
            $shipment = null;
            $order = $obj;
        } elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
            $shipment = $obj;
            $order = $shipment->getOrder();
        }

        $this->y = $this->y ? $this->y : 815;
        $top = $this->y;

        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.45));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.45));
        $page->drawRectangle(25, $top, 570, $top - 55);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
        $this->setDocHeaderCoordinates([25, $top, 570, $top - 55]);
        $this->_setFontRegular($page, 10);

        if ($putOrderId) {
            $page->drawText(__('Order # ') . $order->getRealOrderId(), 35, $top -= 30, 'UTF-8');
            $top +=15;
        }

        $top -=30;
        $page->drawText(
            __('Order Date: ') .
            $this->_localeDate->formatDate(
                $this->_localeDate->scopeDate(
                    $order->getStore(),
                    $order->getCreatedAt(),
                    true
                ),
                IntlDateFormatter::MEDIUM,
                false
            ),
            35,
            $top,
            'UTF-8'
        );

        $top -= 10;
        $page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $top, 275, $top - 25);
        $page->drawRectangle(275, $top, 570, $top - 25);

        /* Calculate blocks info */

        /* Billing Address */
        $billingAddress = $this->_formatAddress($this->addressRenderer->format($order->getBillingAddress(), 'pdf'));

        /* Payment */
        $paymentInfo = $this->_paymentData->getInfoBlock($order->getPayment())->setIsSecureMode(true)->toPdf();
        $paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
        $payment = explode('{{pdf_row_separator}}', $paymentInfo);
        foreach ($payment as $key => $value) {
            if (strip_tags(trim($value)) == '') {
                unset($payment[$key]);
            }
        }
        reset($payment);

        /* Shipping Address and Method */
        if (!$order->getIsVirtual()) {
            /* Shipping Address */
            $shippingAddress = $this->_formatAddress(
                $this->addressRenderer->format($order->getShippingAddress(), 'pdf')
            );
            $shippingMethod = $order->getShippingDescription();
        }

        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->_setFontBold($page, 12);
        $page->drawText(__('Sold to:'), 35, $top - 15, 'UTF-8');

        if (!$order->getIsVirtual()) {
            $page->drawText(__('Ship to:'), 285, $top - 15, 'UTF-8');
        } else {
            $page->drawText(__('Payment Method:'), 285, $top - 15, 'UTF-8');
        }

        $addressesHeight = $this->_calcAddressHeight($billingAddress);
        if (isset($shippingAddress)) {
            $addressesHeight = max($addressesHeight, $this->_calcAddressHeight($shippingAddress));
        }

        $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
        $page->drawRectangle(25, $top - 25, 570, $top - 33 - $addressesHeight);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $this->y = $top - 40;
        $addressesStartY = $this->y;

        foreach ($billingAddress as $value) {
            if ($value !== '') {
                $text = [];
                foreach ($this->string->split($value, 45, true, true) as $_value) {
                    $text[] = $_value;
                }
                foreach ($text as $part) {
                    $page->drawText(strip_tags(ltrim($part)), 35, $this->y, 'UTF-8');
                    $this->y -= 15;
                }
            }
        }

        $addressesEndY = $this->y;

        if (!$order->getIsVirtual()) {
            $this->y = $addressesStartY;
            foreach ($shippingAddress as $value) {
                if ($value !== '') {
                    $text = [];
                    foreach ($this->string->split($value, 45, true, true) as $_value) {
                        $text[] = $_value;
                    }
                    foreach ($text as $part) {
                        $page->drawText(strip_tags(ltrim($part)), 285, $this->y, 'UTF-8');
                        $this->y -= 15;
                    }
                }
            }

            $addressesEndY = min($addressesEndY, $this->y);
            $this->drawPage($addressesEndY, $page);
            $paymentLeft = 35;
            $yPayments = $this->y - 15;
        } else {
            $yPayments = $addressesStartY;
            $paymentLeft = 285;
        }

        foreach ($payment as $value) {
            if (trim($value) != '') {
                //Printing "Payment Method" lines
                $value = preg_replace('/<br[^>]*>/i', "\n", $value);
                foreach ($this->string->split($value, 45, true, true) as $_value) {
                    $page->drawText(strip_tags(trim($_value)), $paymentLeft, $yPayments, 'UTF-8');
                    $yPayments -= 15;
                }
            }
        }

        if ($order->getIsVirtual()) {
            // replacement of Shipments-Payments rectangle block
            $yPayments = min($addressesEndY, $yPayments);
            $page->drawLine(25, $top - 25, 25, $yPayments);
            $page->drawLine(570, $top - 25, 570, $yPayments);
            $page->drawLine(25, $yPayments, 570, $yPayments);

            $this->y = $yPayments - 15;
        } else {
            $this->drawProduct($shippingMethod, $page, $order, $yPayments, $shipment);
        }
    }

    private function drawProduct($shippingMethod, $page, $order, $yPayments, $shipment){
        $topMargin = 15;
        $methodStartY = $this->y;
        $this->y -= 15;

        foreach ($this->string->split($shippingMethod, 45, true, true) as $_value) {
            $page->drawText(strip_tags(trim($_value)), 285, $this->y, 'UTF-8');
            $this->y -= 15;
        }

        /* DrawText Delivery Date / Time / Comment */
        /* Get value of configuration Delivery Dates with scope Website */
        $deliveryGeneralEnabled = $this->_scopeConfig->getValue(self::DELIVERY_GENERAL_ENABLED, ScopeInterface::SCOPE_WEBSITE);
        $deliveryDateInclude = $this->_scopeConfig->getValue(self::DELIVERY_DATE_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
        $deliveryTimeInclude = $this->_scopeConfig->getValue(self::DELIVERY_TIME_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
        $deliveryCommentInclude = $this->_scopeConfig->getValue(self::DELIVERY_COMMENT_INCLUDE, ScopeInterface::SCOPE_WEBSITE);
        $deliveryTimeEnabled = $this->_scopeConfig->getValue(self::DELIVERY_TIME_ENABLE, ScopeInterface::SCOPE_WEBSITE);
        $deliveryCommentEnabled = $this->_scopeConfig->getValue(self::DELIVERY_COMMENT_ENABLE, ScopeInterface::SCOPE_WEBSITE);

        if ($deliveryGeneralEnabled) {
            $currentOrder = $this->shippingDeliveryRepository->getByOrderId($order->getId());
            if ($currentOrder) {
                $deliveryDate = $currentOrder->getDeliveryDate();
                $deliveryTime = $currentOrder->getDeliveryTime();
                $deliveryComment = $currentOrder->getDeliveryComment();
                if (in_array(self::SALES_ORDER_PRINTSHIPMENT, explode(',', $deliveryDateInclude)) && $deliveryDate) {
                    $page->drawText(__('Delivery Date : '), 285, $this->y, 'UTF-8');
                    $page->drawText($deliveryDate, 350, $this->y, 'UTF-8');
                    $this->y -= 15;
                }
                if (in_array(self::SALES_ORDER_PRINTSHIPMENT, explode(',', $deliveryTimeInclude)) && $deliveryTimeEnabled && $deliveryTime) {
                    $page->drawText(__('Delivery Time : '), 285, $this->y, 'UTF-8');
                    $page->drawText($deliveryTime, 355, $this->y, 'UTF-8');
                    $this->y -= 15;
                }
                if (in_array(self::SALES_ORDER_PRINTSHIPMENT, explode(',', $deliveryCommentInclude)) && $deliveryCommentEnabled && $deliveryComment) {
                    $page->drawText(__('Delivery Comment : '), 285, $this->y, 'UTF-8');
                    $this->y -= 15;
                    $deliveryComment = $this->_formatAddress($deliveryComment);
                    foreach ($deliveryComment as $value) {
                        if ($value !== '') {
                            $text = [];
                            foreach ($this->string->split($value, 45, true, true) as $_value) {
                                $text[] = $_value;
                            }
                            foreach ($text as $part) {
                                $page->drawText(strip_tags(ltrim($part)), 285, $this->y, 'UTF-8');
                                $this->y -= 15;
                            }
                        }
                    }
                }
            }
        }
        /* End drawText Delivery Date / Time / Comment */

        $yShipments = $this->y;
        $totalShippingChargesText = "("
            . __('Total Shipping Charges')
            . " "
            . $order->formatPriceTxt($order->getShippingAmount())
            . ")";

        $page->drawText($totalShippingChargesText, 285, $yShipments - $topMargin, 'UTF-8');
        $yShipments -= $topMargin + 10;

        $tracks = [];
        if ($shipment) {
            $tracks = $shipment->getAllTracks();
        }
        if (count($tracks)) {
            $page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
            $page->setLineWidth(0.5);
            $page->drawRectangle(285, $yShipments, 510, $yShipments - 10);
            $page->drawLine(400, $yShipments, 400, $yShipments - 10);
            //$page->drawLine(510, $yShipments, 510, $yShipments - 10);

            $this->_setFontRegular($page, 9);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
            //$page->drawText(__('Carrier'), 290, $yShipments - 7 , 'UTF-8');
            $page->drawText(__('Title'), 290, $yShipments - 7, 'UTF-8');
            $page->drawText(__('Number'), 410, $yShipments - 7, 'UTF-8');

            $yShipments -= 20;
            $this->_setFontRegular($page, 8);
            foreach ($tracks as $track) {
                $maxTitleLen = 45;
                $endOfTitle = strlen($track->getTitle()) > $maxTitleLen ? '...' : '';
                $truncatedTitle = substr($track->getTitle(), 0, $maxTitleLen) . $endOfTitle;
                $page->drawText($truncatedTitle, 292, $yShipments, 'UTF-8');
                $page->drawText($track->getNumber(), 410, $yShipments, 'UTF-8');
                $yShipments -= $topMargin - 5;
            }
        } else {
            $yShipments -= $topMargin - 5;
        }

        $currentY = min($yPayments, $yShipments);

        // replacement of Shipments-Payments rectangle block
        $page->drawLine(25, $methodStartY, 25, $currentY);
        //left
        $page->drawLine(25, $currentY, 570, $currentY);
        //bottom
        $page->drawLine(570, $currentY, 570, $methodStartY);
        //right

        $this->y = $currentY;
        $this->y -= 15;
    }

    private function drawPage($addressesEndY, $page)
    {
        $this->y = $addressesEndY;

        $page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 275, $this->y - 25);
        $page->drawRectangle(275, $this->y, 570, $this->y - 25);

        $this->y -= 15;
        $this->_setFontBold($page, 12);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $page->drawText(__('Payment Method'), 35, $this->y, 'UTF-8');
        $page->drawText(__('Shipping Method:'), 285, $this->y, 'UTF-8');

        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));

        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
    }
}
