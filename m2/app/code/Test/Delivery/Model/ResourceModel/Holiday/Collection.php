<?php

namespace Test\Delivery\Model\ResourceModel\Holiday;

use Test\Delivery\Model\ResourceModel\AbstractCollection;

/**
 * Class Collection
 * @package Test\Delivery\Model\ResourceModel\Holiday
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'holiday_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_delivery_holiday_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'holiday_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Test\Delivery\Model\Holiday', 'Test\Delivery\Model\ResourceModel\Holiday');
    }


    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('test_delivery_holidays_store', 'holiday_id');
        $this->_map['fields']['holiday_id'] = 'main_table.holiday_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * @return AbstractCollection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _afterLoad()
    {

        $this->performAfterLoad('test_delivery_holidays_store', 'holiday_id');

        return parent::_afterLoad();
    }
}
