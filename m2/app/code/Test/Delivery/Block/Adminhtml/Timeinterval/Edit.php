<?php

namespace Test\Delivery\Block\Adminhtml\Timeinterval;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;

/**
 * Class Edit
 * @package Test\Delivery\Block\Adminhtml\Timeinterval
 */
class Edit extends Container
{
    /**
     * Edit constructor.
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {

        parent::__construct($context, $data);
    }

    /**
     * Initialize Slider edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'time_interval_id';
        $this->_blockGroup = 'Test_Delivery';
        $this->_controller = 'adminhtml_timeinterval';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Time Interval'));

        $this->buttonList->update('delete', 'label', __('Delete Time Interval'));
    }

    /**
     * Retrieve text for header element depending on loaded Slider
     *
     * @return string
     */
    public function getHeaderText()
    {
        return __('New  Time Interval');
    }
}
