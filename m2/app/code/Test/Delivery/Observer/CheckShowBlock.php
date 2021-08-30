<?php
namespace Test\Delivery\Observer;

use Braintree\Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckShowBlock implements ObserverInterface
{
    protected $_scopeConfig;

    protected $dataHelper;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Test\Delivery\Helper\Data $dataHelper
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->dataHelper = $dataHelper;
    }

    public function execute(Observer $observer)
    {
        $this->checkDeliveryDate($observer, 'delivery_date');
        $this->checkDeliveryDate($observer, 'delivery_time');
        $this->checkDeliveryDate($observer, 'delivery_comment');
    }

    /**
     * @param $observer
     * @param $name
     * @throws Exception
     */
    public function checkDeliveryDate($observer, $name)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getLayout();
        $fullActionName = $observer->getData('full_action_name');

        try {
            switch ($name) {
                case 'delivery_date':
                    $block = $layout->getBlock('delivery_date');
                    if ($block) {
                        $dateIncludeTo = $this->dataHelper->getDeliveryDateInclude();
                        $dateIncludeTo = explode(',', $dateIncludeTo);

                        $dateDisplayOn = $this->dataHelper->getDeliveryDateDisplayOn();
                        $dateDisplayOn = explode(',', $dateDisplayOn);

                        if (!in_array($fullActionName, $dateIncludeTo)) {
                            $layout->unsetElement('delivery_date');
                        }

                        if (!in_array($fullActionName, $dateDisplayOn)) {
                            $layout->unsetElement('delivery_date_admin');
                        }
                    }
                    break;
                case 'delivery_time':
                    $block = $layout->getBlock('delivery_time');
                    if ($block) {
                        $dateIncludeTo = $this->dataHelper->getDeliveryTimeInclude();
                        $dateIncludeTo = explode(',', $dateIncludeTo);
                        $dateDisplayOn = $this->dataHelper->getDeliveryDateDisplayOn();
                        $dateDisplayOn = explode(',', $dateDisplayOn);

                        if (!in_array($fullActionName, $dateIncludeTo)) {
                            $layout->unsetElement('delivery_time');
                        }

                        if (!in_array($fullActionName, $dateDisplayOn)) {
                            $layout->unsetElement('delivery_time_admin');
                        }
                    }
                    break;
                default:
                    $block = $layout->getBlock('delivery_comment');
                    if ($block) {
                        $dateIncludeTo = $this->dataHelper->getDeliveryCommentInclude();
                        $dateIncludeTo = explode(',', $dateIncludeTo);
                        $dateDisplayOn = $this->dataHelper->getDeliveryDateDisplayOn();
                        $dateDisplayOn = explode(',', $dateDisplayOn);


                        if (!in_array($fullActionName, $dateIncludeTo)) {
                            $layout->unsetElement('delivery_comment');
                        }

                        if (!in_array($fullActionName, $dateDisplayOn)) {
                            $layout->unsetElement('delivery_comment_admin');
                        }
                    }
                    break;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
