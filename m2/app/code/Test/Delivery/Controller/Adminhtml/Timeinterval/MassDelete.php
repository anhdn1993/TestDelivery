<?php

namespace Test\Delivery\Controller\Adminhtml\Timeinterval;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Test\Delivery\Model\ResourceModel\TimeInterval\CollectionFactory;
use Test\Delivery\Api\TimeIntervalRepositoryInterface;

/**
 * Class MassDelete
 * @package Test\Delivery\Controller\Adminhtml\Timeinterval
 */
class MassDelete extends Action
{
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var TimeIntervalRepositoryInterface
     */
    protected $timeIntervalRepository;


    /**
     * MassDelete constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param TimeIntervalRepositoryInterface $timeIntervalRepository
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        TimeIntervalRepositoryInterface $timeIntervalRepository,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->timeIntervalRepository= $timeIntervalRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function execute()
    {

        $ids = $this->getRequest()->getParam('selected');

        if (!$ids == 'false') {
            $collection = $this->collectionFactory->create();
            $collectionSize = $collection->getSize();

            foreach ($collection as $item) {
                $this->timeIntervalRepository->delete($item);
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        } elseif ($ids) {
            try {
                foreach ($ids as $id) {
                    $model = $this->timeIntervalRepository->get($id);
                    $this->timeIntervalRepository->delete($model);
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
