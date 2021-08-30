<?php

namespace Test\Delivery\Model\ResourceModel\Order\Invoice\Grid;

use Magento\Sales\Model\ResourceModel\Order\Invoice\Orders\Grid\Collection as OriginalCollection;

class Collection extends OriginalCollection
{

    protected function _renderFiltersBefore()
    {
        $joinTable = $this->getTable('test_shipping_delivery');
        $this->getSelect()->joinLeft($joinTable.' as cpev', 'main_table.order_id = cpev.order_id', array('delivery_date', 'delivery_time', 'delivery_comment'));
        parent::_renderFiltersBefore();
    }
}
