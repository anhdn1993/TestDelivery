<?php

namespace Test\Delivery\Controller\Adminhtml\Timeinterval;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Test\Delivery\Api\TimeIntervalRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Test\Delivery\Model\TimeIntervalFactory;

/**
 * Class Delete
 * @package Test\Delivery\Controller\Adminhtml\Timeinterval
 */
class Delete extends Action
{
    /**
     * @var TimeIntervalRepositoryInterface
     */
    protected $timeIntervalRepository;

    /**
     * Delete constructor.
     * @param TimeIntervalRepositoryInterface $timeIntervalRepository
     * @param Context $context
     */
    public function __construct(
        TimeIntervalRepositoryInterface $timeIntervalRepository,
        Context $context
    ) {
        $this->timeIntervalRepository = $timeIntervalRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('time_interval_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->timeIntervalRepository->get($id);
                $this->timeIntervalRepository->delete($model);
                $this->messageManager->addSuccess(__('This item has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['time_interval_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete'));
        return $resultRedirect->setPath('testdelivery/*/');
    }
}
