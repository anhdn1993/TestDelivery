<?php

namespace Test\Delivery\Controller\Adminhtml\Holiday;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Test\Delivery\Api\DeliveryHolidayRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Test\Delivery\Model\ResourceModel\HolidayFactory;

/**
 * Class Delete
 * @package Test\Delivery\Controller\Adminhtml\Timeinterval
 */
class Delete extends Action
{
    /**
     * @var DeliveryHolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * Delete constructor.
     * @param DeliveryHolidayRepositoryInterface $holidayRepository
     * @param Context $context
     */
    public function __construct(
        DeliveryHolidayRepositoryInterface $holidayRepository,
        Context $context
    ) {
        $this->holidayRepository = $holidayRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('holiday_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->holidayRepository->get($id);
                $this->holidayRepository->delete($model);
                $this->messageManager->addSuccessMessage(__('This item has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a item to delete'));
        return $resultRedirect->setPath('testdelivery/*/');
    }
}
