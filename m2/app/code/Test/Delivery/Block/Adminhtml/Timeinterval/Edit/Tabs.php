<?php

namespace Test\Delivery\Block\Adminhtml\Timeinterval\Edit;

/**
 * @method Tabs setTitle(string $title)
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('timeinterval_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Time Interval Information'));
    }
}
