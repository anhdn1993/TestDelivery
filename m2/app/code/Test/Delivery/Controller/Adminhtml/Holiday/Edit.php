<?php

namespace Test\Delivery\Controller\Adminhtml\Holiday;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\Delivery\Model\HolidayFactory;
use Test\Delivery\Api\DeliveryHolidayRepositoryInterface;

/**
 * Class Edit
 * @package Test\Delivery\Controller\Adminhtml\Holiday
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Test_Delivery::holiday';

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var DeliveryHolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var HolidayFactory
     */
    protected $holidayFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;


    /**
     * Edit constructor.
     * @param PageFactory $resultPageFactory
     * @param DeliveryHolidayRepositoryInterface $deliveryHolidayRepository
     * @param HolidayFactory $holidayFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        DeliveryHolidayRepositoryInterface $deliveryHolidayRepository,
        HolidayFactory $holidayFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->holidayRepository = $deliveryHolidayRepository;
        $this->holidayFactory = $holidayFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|ResponseInterface|Redirect|ResultInterface|Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $holidayModel = $this->initHoliday();

        $id = (int)$this->getRequest()->getParam('holiday_id');

        if ($id) {
            if (!$holidayModel->getId()) {
                $holidayModel = $this->holidayRepository->get($id);
                $this->messageManager->addErrorMessage(__('This holiday no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'testdelivery/*/edit',
                    [
                        'holiday_id' => $holidayModel->getId(),
                        '_current'  => true
                    ]
                );

                return $resultRedirect;
            }
        }


        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Test_Delivery::holiday');
        $resultPage->getConfig()->getTitle()
            ->set(__('Holiday'))
            ->prepend($holidayModel->getId() ? $holidayModel->getName() : __('New Holiday'));

        return $resultPage;
    }

    /**
     * @return \Test\Delivery\Api\Data\DeliveryHolidayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initHoliday()
    {
        $id = (int)$this->getRequest()->getParam('holiday_id');

        $model = $this->holidayFactory->create();
        if ($id) {
            $model = $this->holidayRepository->get($id);
        }

        $this->coreRegistry->register('testdelivery_holiday', $model);

        return $model;
    }
}
