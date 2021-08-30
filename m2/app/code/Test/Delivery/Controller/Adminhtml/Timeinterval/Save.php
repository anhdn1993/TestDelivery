<?php

namespace Test\Delivery\Controller\Adminhtml\Timeinterval;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\Api\DataObjectHelper;
use RuntimeException;
use Test\Delivery\Model\TimeIntervalFactory;
use Test\Delivery\Api\TimeIntervalRepositoryInterface;
use Test\Delivery\Api\Data\TimeIntervalInterface;
use Magento\Backend\App\Action;

/**
 * Class Save
 * @package Test\Delivery\Controller\Adminhtml\Timeinterval
 */
class Save extends Action
{
    /**
     * @var TimeIntervalFactory
     */
    protected $timeInternalFactory;

    /**
     * @var TimeIntervalRepositoryInterface
     */
    protected $timeInternalRepository;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Save constructor.
     * @param TimeIntervalFactory $timeInternalFactory
     * @param TimeIntervalRepositoryInterface $timeInternalRepository
     * @param DataObjectHelper $dataObjectHelper
     * @param Context $context
     */
    public function __construct(
        TimeIntervalFactory $timeInternalFactory,
        TimeIntervalRepositoryInterface $timeInternalRepository,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->timeInternalFactory = $timeInternalFactory;
        $this->timeInternalRepository = $timeInternalRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($this->getRequest()->getPost('timeinterval')) {
            $data   = $this->getRequest()->getPost('timeinterval');
            $id = !empty($data['time_interval_id']) ? (int)$data['time_interval_id'] : null;


            if ($id) {
                $timeInternal = $this->timeInternalRepository->get($id);
            } else {
                $timeInternal = $this->timeInternalFactory->create();
            }

            try {
//                $this->dataObjectHelper->populateWithArray($timeInternal, $data, TimeIntervalInterface::class);
                $timeInternal->setData($data);

                $this->timeInternalRepository->save($timeInternal);
                $this->messageManager->addSuccess(__('The Time Interval has been saved.'));

                $resultRedirect->setPath('testdelivery/*/');

                return $resultRedirect;
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Time Interval.'));
            }


            return $resultRedirect;
        }
    }
}
