<?php

namespace Test\Delivery\Model;

use Test\Delivery\Api\Data\ShippingDeliveryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ShippingDeliveryRepository
 * @package Test\Delivery\Model
 */
class ShippingDeliveryRepository implements \Test\Delivery\Api\ShippingDeliveryRepositoryInterface
{
    /**
     * @var \Test\Delivery\Model\ShippingDeliveryRepository
     */
    protected $modelFactory;

    /**
     * @var \Test\Delivery\Model\ResourceModel\ShippingDelivery
     */
    protected $resourceModel;

    /**
     * @var \Test\Delivery\Model\ResourceModel\ShippingDelivery\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Test\Delivery\Api\Data\ShippingDeliverySearchResultsInterface
     */
    protected $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var ResourceModel\ShippingDeliveryFactory
     */
    private $shippingDeliveryFactory;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * ShippingDeliveryRepository constructor.
     * @param ShippingDeliveryFactory $modelFactory
     * @param ResourceModel\ShippingDelivery $resourceModel
     * @param ResourceModel\ShippingDelivery\CollectionFactory $collectionFactory
     * @param ResourceModel\ShippingDeliveryFactory $shippingDeliveryFactory
     * @param \Test\Delivery\Api\Data\ShippingDeliverySearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Test\Delivery\Model\ShippingDeliveryFactory $modelFactory,
        \Test\Delivery\Model\ResourceModel\ShippingDelivery $resourceModel,
        \Test\Delivery\Model\ResourceModel\ShippingDelivery\CollectionFactory $collectionFactory,
        \Test\Delivery\Model\ShippingDeliveryFactory $shippingDeliveryFactory,
        \Test\Delivery\Api\Data\ShippingDeliverySearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->shippingDeliveryFactory = $shippingDeliveryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Test\Delivery\Api\Data\ShippingDeliveryInterface $model)
    {
        try {
            $this->resourceModel->save($model);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save Time Interval'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($modelId)
    {
        $model = $this->modelFactory->create();
        $this->resourceModel->load($model, $modelId);
        if (!$model->getDeliveryId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Requested time interval doesn\'t exist'));
        }
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Test\Delivery\Api\Data\ShippingDeliveryInterface $model)
    {
        $id = $model->getShippingDeliveryId();
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(__('Unable to remove Time Intervals %1', $id));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($modelId)
    {
        $model = $this->get($modelId);
        return $this->delete($model);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {

        /** @var \Test\Delivery\Model\ResourceModel\ShippingDelivery\Collection $collection */
        $collection = $this->collectionFactory->create();

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        /** @var \Magento\Framework\Api\SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        /** @var \Test\Delivery\Api\Data\ShippingDeliverySearchResultsInterface$searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param ResourceModel\ShippingDelivery\Collection $collection
     */
    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Test\Delivery\Model\ResourceModel\ShippingDelivery\Collection $collection
    ) {
        $fields = [];
        $conditions = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $field = $filter->getField();
            $condition = $filter->getConditionType() ?: 'eq';
            $value = $filter->getValue();

            $fields[] = $field;
            $conditions[] = [ $condition => $value ];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param int $quoteId
     * @return ShippingDeliveryInterface|ResourceModel\ShippingDelivery|ShippingDelivery
     */
    public function getByQuoteId($quoteId)
    {
        $filter = $this->filterBuilder->create()
            ->setField(ShippingDeliveryInterface::QUOTE_ID)
            ->setValue($quoteId)
            ->setConditionType('eq');

        $filterGroup = $this->filterGroupBuilder
            ->addFilter($filter)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->setFilterGroups([$filterGroup])
            ->create();

        $result = $this->getList($searchCriteria);

        $delivery = false;

        foreach ($result->getItems() as $item) {
            $delivery = $item;
            break;
        }

        return $delivery;
    }

    /**
     * @param $orderId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getByOrderId($orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ShippingDeliveryInterface::ORDER_ID, $orderId, 'eq')->create();

        $result = $this->getList($searchCriteria);

        $delivery = false;

        foreach ($result->getItems() as $item) {
            $delivery = $item;
        }

        if (!$delivery) {
            $delivery = $this->shippingDeliveryFactory->create();
        }

        return $delivery;
    }
}
