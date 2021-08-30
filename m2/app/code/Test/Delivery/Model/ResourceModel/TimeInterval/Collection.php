<?php

namespace Test\Delivery\Model\ResourceModel\TimeInterval;

use Test\Delivery\Model\ResourceModel\AbstractCollection;
use Zend_Db_Select;

/**
 * Class Collection
 * @package Test\Delivery\Model\ResourceModel\TimeInterval
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'time_interval_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_delivery_timeinterval_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'timeinterval_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Test\Delivery\Model\TimeInterval', 'Test\Delivery\Model\ResourceModel\TimeInterval');
    }


    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('test_delivery_time_interval_store', 'time_interval_id');
        $this->_map['fields']['time_interval_id'] = 'main_table.time_interval_id';
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

        $this->performAfterLoad('test_delivery_time_interval_store', 'time_interval_id');

        return parent::_afterLoad();
    }
}
