<?php

namespace Test\Delivery\Controller\Adminhtml\Holiday;

use Test\Delivery\Api\DeliveryHolidayRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Test\Delivery\Model\ResourceModel\Holiday\CollectionFactory;

/**
 * Class MassDelete
 * @package Test\Delivery\Controller\Adminhtml\Holiday
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
     * @var DeliveryHolidayRepositoryInterface
     */
    protected $holidayRepository;


    /**
     * MassDelete constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DeliveryHolidayRepositoryInterface $deliveryHolidayRepository
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        DeliveryHolidayRepositoryInterface $deliveryHolidayRepository,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->holidayRepository= $deliveryHolidayRepository;
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
                $this->holidayRepository->delete($item);
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        } elseif ($ids) {
            try {
                foreach ($ids as $id) {
                    $model = $this->holidayRepository->get($id);
                    $this->holidayRepository->delete($model);
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
