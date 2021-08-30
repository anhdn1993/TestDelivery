<?php

namespace Test\Delivery\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Holiday
 * @package Test\Delivery\Model\ResourceModel
 */
class Holiday extends AbstractDb
{
    protected $_holidayStoreTable;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
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
        $this->_init('test_delivery_holidays', 'holiday_id');
        $this->_holidayStoreTable = $this->getTable('test_delivery_holidays_store');
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
         * save stores to table test_delivery_holidays_store
         */
        $stores = $object->getStoreIds();

        if (!empty($stores)) {
            $condition = ['holiday_id = ?' => $object->getId()];
            $connection->delete($this->_holidayStoreTable, $condition);
            $insertedStoreIds = [];
            foreach ($stores as $storeId) {
                if (in_array($storeId, $insertedStoreIds)) {
                    continue;
                }

                $insertedStoreIds[] = $storeId;
                $storeInsert = ['store_id' => $storeId, 'holiday_id' => $object->getId()];
                $connection->insert($this->_holidayStoreTable, $storeInsert);
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
            ->from(['cbs' => $this->getTable('test_delivery_holidays_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . 'holiday_id' . ' = cb.' . 'holiday_id',
                []
            )
            ->where('cb.' . 'holiday_id' . ' = :holiday_id');

        return $connection->fetchCol($select, ['holiday_id' => (int)$id]);
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
            ->from(['cbs' => $this->getTable('test_delivery_holidays_store')], 'holiday_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . 'holiday_id' . ' = cb.' . 'holiday_id',
                ['cb.to_day', 'cb.from_day', 'cb.to_month',  'cb.from_month', 'cb.to_year', 'cb.from_year', 'cb.each_year', 'cb.each_month']
            )
            ->where('cbs.' . 'store_id' . ' = ' . $storeId)
            ->orWhere('cbs.' . 'store_id' . ' = 0');

        $items = $connection->fetchAll($select);
        $result = [];
        foreach ($items as $item) {
            $varienObject = new \Magento\Framework\DataObject();
            $varienObject->setData($item);
            $result[] = $varienObject;
        }
        return $result;
    }
}
