<?php

namespace Test\Delivery\Model;

/**
 * Class TimeIntervalRepository
 * @package Test\Delivery\Model
 */
class TimeIntervalRepository implements \Test\Delivery\Api\TimeIntervalRepositoryInterface
{
    /**
     * @var \Test\Delivery\Model\TimeIntervalRepository
     */
    protected $modelFactory;

    /**
     * @var \Test\Delivery\Model\ResourceModel\TimeInterval
     */
    protected $resourceModel;

    /**
     * @var \Test\Delivery\Model\ResourceModel\TimeInterval\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Test\Delivery\Api\Data\TimeIntervalSearchResultsInterface
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
     * TimeIntervalRepository constructor.
     * @param TimeIntervalFactory $modelFactory
     * @param ResourceModel\TimeInterval $resourceModel
     * @param ResourceModel\TimeInterval\CollectionFactory $collectionFactory
     * @param \Test\Delivery\Api\Data\TimeIntervalSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Test\Delivery\Model\TimeIntervalFactory $modelFactory,
        \Test\Delivery\Model\ResourceModel\TimeInterval $resourceModel,
        \Test\Delivery\Model\ResourceModel\TimeInterval\CollectionFactory $collectionFactory,
        \Test\Delivery\Api\Data\TimeIntervalSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Test\Delivery\Api\Data\TimeIntervalInterface $model)
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
        if (!$model->getTimeIntervalId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Requested time interval doesn\'t exist'));
        }
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Test\Delivery\Api\Data\TimeIntervalInterface $model)
    {
        $id = $model->getTimeIntervalId();
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

        /** @var \Test\Delivery\Api\Data\TimeIntervalSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param ResourceModel\TimeInterval\Collection $collection
     */
    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Test\Delivery\Model\ResourceModel\TimeInterval\Collection $collection
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
}
