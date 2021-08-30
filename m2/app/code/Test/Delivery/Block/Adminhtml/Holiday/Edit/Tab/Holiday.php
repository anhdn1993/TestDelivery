<?php

namespace Test\Delivery\Block\Adminhtml\Holiday\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Class Holiday
 * @package Test\Delivery\Block\Adminhtml\Holiday\Edit\Tab
 */
class Holiday extends Generic implements TabInterface
{
    /**
     * Core system store model
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * Holiday constructor.
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
        $banner = $this->_coreRegistry->registry('testdelivery_holiday');
        $form   = $this->_formFactory->create();
        $form->setHtmlIdPrefix('holiday_');
        $form->setFieldNameSuffix('holiday');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General'),
            'class'  => 'fieldset-wide'
        ]);

        if ($banner->getId()) {
            $fieldset->addField(
                'holiday_id',
                'hidden',
                ['name' => 'holiday_id']
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

        $fieldset->addField(
            'each_year',
            'select',
            [
                'name'     => 'each_year',
                'label'    => __('Each year'),
                'title'    => __('Each year'),
                'required' => true,
                'values'   => $this->getYesno(),
            ]
        );

        $fieldset->addField(
            'each_month',
            'select',
            [
                'name'     => 'each_month',
                'label'    => __('Each month'),
                'title'    => __('Each month'),
                'required' => true,
                'values'   => $this->getYesno(),
            ]
        );

        $fieldset->addField(
            'from_day',
            'select',
            [
                'name'     => 'from_day',
                'label'    => __('From day'),
                'title'    => __('From day'),
                'required' => true,
                'values'   => $this->getDays()
            ]
        );

        $fieldset->addField('from_month', 'select', [
            'name'     => 'from_month',
            'label'    => __('From month'),
            'title'    => __('From month'),
            'required' => true,
            'values'   => $this->getMonth()
        ]);

        $fieldset->addField('from_year', 'select', [
            'name'     => 'from_year',
            'label'    => __('From year'),
            'title'    => __('From year'),
            'required' => true,
            'values'   => $this->getYear()
        ]);

        $fieldset->addField(
            'to_day',
            'select',
            [
                'name'     => 'to_day',
                'label'    => __('To day'),
                'title'    => __('To day'),
                'required' => true,
                'values'   => $this->getDays()
            ]
        );

        $fieldset->addField(
            'to_month',
            'select',
            [
                'name'     => 'to_month',
                'label'    => __('To month'),
                'title'    => __('To month'),
                'required' => true,
                'values'   => $this->getMonth()
            ]
        );

        $fieldset->addField('to_year', 'select', [
            'name'     => 'to_year',
            'label'    => __('To year'),
            'title'    => __('To year'),
            'required' => true,
            'values'   => $this->getYear()
        ]);

        $fieldset->addField('description', 'text', [
            'name'     => 'description',
            'label'    => __('Description'),
            'title'    => __('Description'),
            'required' => false,
        ]);

        $form->addValues($banner->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    private function getYesno()
    {
        $options = [
            ['label' => __('Yes'), 'value' => 1],
            ['label' => __('No'), 'value' => 0]
        ];
        return $options;
    }

    private function getYear()
    {
        $startYear = date('Y');
        $limit = $startYear + 5;
        $options = [];
        for ($i = $startYear; $i < $limit; $i++) {
            $options[] = ['label' => __($i), 'value' => $i];
        }
        return $options;
    }

    private function getMonth()
    {
        $options = [
            ['label' => __('January'), 'value' => 1],
            ['label' => __('February'), 'value' => 2],
            ['label' => __('March'), 'value' => 3],
            ['label' => __('April'), 'value' => 4],
            ['label' => __('May'), 'value' => 5],
            ['label' => __('June'), 'value' => 6],
            ['label' => __('July'), 'value' => 7],
            ['label' => __('August'), 'value' => 8],
            ['label' => __('September'), 'value' => 9],
            ['label' => __('October'), 'value' => 10],
            ['label' => __('November'), 'value' => 11],
            ['label' => __('December'), 'value' => 12]
        ];
        return $options;
    }

    private function getDays()
    {
        $options = [];
        for ($i = 1; $i <= 31; $i++) {
            $options[] = ['label' => __($i), 'value' => $i];
        }

        return $options;
    }
}
