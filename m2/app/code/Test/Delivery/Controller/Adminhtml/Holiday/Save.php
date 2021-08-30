<?php

namespace Test\Delivery\Controller\Adminhtml\Holiday;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\Api\DataObjectHelper;
use RuntimeException;
use Test\Delivery\Model\HolidayFactory;
use Test\Delivery\Api\DeliveryHolidayRepositoryInterface;
use Magento\Backend\App\Action;

/**
 * Class Save
 * @package Test\Delivery\Controller\Adminhtml\Holiday
 */
class Save extends Action
{
    /**
     * @var HolidayFactory
     */
    protected $holidayFactory;

    /**
     * @var DeliveryHolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Save constructor.
     * @param HolidayFactory $holidayFactory
     * @param DeliveryHolidayRepositoryInterface $deliveryHolidayRepository
     * @param DataObjectHelper $dataObjectHelper
     * @param Context $context
     */
    public function __construct(
        HolidayFactory $holidayFactory,
        DeliveryHolidayRepositoryInterface $deliveryHolidayRepository,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->holidayFactory = $holidayFactory;
        $this->holidayRepository = $deliveryHolidayRepository;
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

        if ($this->getRequest()->getPost('holiday')) {
            $data   = $this->getRequest()->getPost('holiday');
            $id = !empty($data['holiday_id']) ? (int)$data['holiday_id'] : null;


            if ($id) {
                $holiday = $this->holidayRepository->get($id);
            } else {
                $holiday = $this->holidayFactory->create();
            }

            try {
                $holiday->setData($data);
                $this->holidayRepository->save($holiday);
                $this->messageManager->addSuccessMessage(__('The holiday has been saved.'));

                $resultRedirect->setPath('testdelivery/*/');

                return $resultRedirect;
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e, __('Something went wrong while saving the Holiday.'));
            }


            return $resultRedirect;
        }
    }
}
