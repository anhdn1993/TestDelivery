<?php

namespace Test\Delivery\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class TimeInterval
 * @package Test\Delivery\Model\ResourceModel
 */
class TimeInterval extends AbstractDb
{
    protected $_timeIntervalStoreTable;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * TimeInterval constructor.
     * @param Context $context
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        MetadataPool $metadataPool
    ) {
        $this->metadataPool = $metadataPool;
        $this->entityManager = $entityManager;
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('test_delivery_time_interval', 'time_interval_id');
        $this->_timeIntervalStoreTable = $this->getTable('test_delivery_time_interval_store');
    }

    /**
     * Perform actions after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $connection = $this->getConnection();

        /**
         * save stores to table test_delivery_time_interval_store
         */
        $stores = $object->getStoreIds();

        if (!empty($stores)) {
            $condition = ['time_interval_id = ?' => $object->getId()];
            $connection->delete($this->_timeIntervalStoreTable, $condition);
            $insertedStoreIds = [];
            foreach ($stores as $storeId) {
                if (in_array($storeId, $insertedStoreIds)) {
                    continue;
                }

                $insertedStoreIds[] = $storeId;
                $storeInsert = ['store_id' => $storeId, 'time_interval_id' => $object->getId()];
                $connection->insert($this->_timeIntervalStoreTable, $storeInsert);
            }
        }

        return $this;
    }

    /**
     * @param AbstractModel $object
     * @return $this|AbstractDb
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $id =   $object->getId();
        if ($id) {
            $storeId = $this->lookupStoreIds($object->getId());
            $object->setStoreIds($storeId);
        }

        return $this;
    }

    /**
     * @param $id
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['cbs' => $this->getTable('test_delivery_time_interval_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . 'time_interval_id' . ' = cb.' . 'time_interval_id',
                []
            )
            ->where('cb.' . 'time_interval_id' . ' = :time_interval_id');

        return $connection->fetchCol($select, ['time_interval_id' => (int)$id]);
    }

    /**
     * @param $storeId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDataByStore($storeId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['cbs' => $this->getTable('test_delivery_time_interval_store')], 'time_interval_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . 'time_interval_id' . ' = cb.' . 'time_interval_id',
                ['cb.to', 'cb.from', 'cb.position']
            )
            ->where('cbs.' . 'store_id' . ' = ' . $storeId)
            ->orWhere('cbs.' . 'store_id' . ' = 0')
            ->order('cb.position asc');

        return $connection->fetchAll($select);
    }
}
