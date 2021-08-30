<?php

namespace Test\Delivery\Block\Adminhtml\Holiday\Edit;

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
        $this->setId('holiday_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Holiday Information'));
    }
}
