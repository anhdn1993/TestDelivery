<?php

namespace Test\Delivery\Block\Adminhtml\Sales\Order;

/**
 * Class Delivery
 */
class Delivery extends \Test\Delivery\Block\Sales\Order\Info\Delivery
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('Test_Delivery::sales/order/delivery.phtml');
    }
}
