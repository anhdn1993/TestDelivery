<?php

namespace Test\Delivery\Block\Adminhtml\Timeinterval\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Class Timeinterval
 * @package Test\Delivery\Block\Adminhtml\Timeinterval\Edit\Tab
 */
class Timeinterval extends Generic implements TabInterface
{

    /**
     * Core system store model
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;


    /**
     * Timeinterval constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore     = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Properties');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $banner = $this->_coreRegistry->registry('testdelivery_timeinterval');

        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('timeinterval_');
        $form->setFieldNameSuffix('timeinterval');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General'),
            'class'  => 'fieldset-wide'
        ]);

        if ($banner->getId()) {
            $fieldset->addField(
                'time_interval_id',
                'hidden',
                ['name' => 'time_interval_id']
            );
        }

        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
                'name'     => 'store_ids[]',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
        $fieldset->addField('from', 'text', [
            'name'     => 'from',
            'label'    => __('From'),
            'title'    => __('From'),
            'required' => true,
        ]);

        $fieldset->addField('to', 'text', [
            'name'     => 'to',
            'label'    => __('To'),
            'title'    => __('To'),
            'required' => true,
        ]);

        $fieldset->addField('position', 'text', [
            'name'     => 'position',
            'label'    => __('Position'),
            'title'    => __('Position'),
            'required' => true,
        ]);

        $form->addValues($banner->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
