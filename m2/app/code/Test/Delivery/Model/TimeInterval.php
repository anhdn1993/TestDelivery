<?php

namespace Test\Delivery\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Test\Delivery\Model\ResourceModel\TimeInterval as ResourceTimeInterval;

/**
 * Class TimeInterval
 *
 * @api
 * @method Page setStoreIds(array $storeIds)
 * @method array getStoreIds()
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @since 100.0.2
 */
class TimeInterval extends AbstractModel implements \Test\Delivery\Api\Data\TimeIntervalInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'test_delivery_timeinterval';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'test_delivery_timeinterval';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'test_delivery_timeinterval';

    /**
     * Slider Collection
     *
     * @var Collection
     */
    protected $sliderCollection;

    /**
     * Slider Collection Factory
     *
     * @var sliderCollectionFactory
     */
    protected $sliderCollectionFactory;

    /**
     * TimeInterval constructor.
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceTimeInterval::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeIntervalId()
    {
        return $this->_getData('time_interval_id');
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeIntervalId($time_interval_id)
    {
        return $this->setData('time_interval_id', $time_interval_id);
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        return $this->_getData(self::FROM);
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($from)
    {
        return $this->setData(self::FROM, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getTo()
    {
        return $this->_getData(self::TO);
    }

    /**
     * {@inheritdoc}
     */
    public function setTo($from)
    {
        return $this->setData(self::TO, $from);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->_getData(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($from)
    {
        return $this->setData(self::POSITION, $from);
    }
}
