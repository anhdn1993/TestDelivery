<?php

namespace Test\Delivery\Model;

use Test\Delivery\Api\Data\ShippingDeliveryInterface;
use Test\Delivery\Model\ResourceModel\ShippingDelivery as ResourceShippingDelivery;
use Magento\Framework\Model\AbstractModel;

class ShippingDelivery extends AbstractModel implements ShippingDeliveryInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'test_shipping_delivery';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'test_shipping_delivery';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_shipping_delivery';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceShippingDelivery::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryId()
    {
        return $this->_getData(self::DELIVERY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_getData(self::DELIVERY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveryId($from)
    {
        return $this->setData(self::DELIVERY_ID, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteId()
    {
        return $this->_getData(self::QUOTE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteId($from)
    {
        return $this->setData(self::QUOTE_ID, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        return $this->_getData(self::ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($from)
    {
        return $this->setData(self::ORDER_ID, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryDate()
    {
        return $this->_getData(self::DELIVERY_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveryDate($from)
    {
        return $this->setData(self::DELIVERY_DATE, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryTime()
    {
        return $this->_getData(self::DELIVERY_TIME);
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveryTime($from)
    {
        return $this->setData(self::DELIVERY_TIME, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryComment()
    {
        return $this->_getData(self::DELIVERY_COMMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveryComment($from)
    {
        return $this->setData(self::DELIVERY_COMMENT, $from);
    }
}
