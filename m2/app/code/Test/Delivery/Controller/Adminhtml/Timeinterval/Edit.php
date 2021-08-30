<?php

namespace Test\Delivery\Controller\Adminhtml\Timeinterval;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Test\Delivery\Model\TimeIntervalFactory;
use Test\Delivery\Api\TimeIntervalRepositoryInterface;

/**
 * Class Edit
 * @package Test\Delivery\Controller\Adminhtml\Timeinterval
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Test_Delivery::timeinterval';

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var TimeIntervalRepositoryInterface
     */
    protected $timeIntervalRepository;

    /**
     * @var TimeIntervalFactory
     */
    protected $timeIntervalFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;


    /**
     * Edit constructor.
     * @param PageFactory $resultPageFactory
     * @param TimeIntervalRepositoryInterface $timeIntervalRepository
     * @param TimeIntervalFactory $timeIntervalFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        TimeIntervalRepositoryInterface $timeIntervalRepository,
        TimeIntervalFactory $timeIntervalFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->timeIntervalRepository = $timeIntervalRepository;
        $this->timeIntervalFactory = $timeIntervalFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|ResponseInterface|Redirect|ResultInterface|Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $timeIntervalsModel = $this->initSlider();

        $id = (int)$this->getRequest()->getParam('time_interval_id');

        if ($id) {
            if (!$timeIntervalsModel->getId()) {
                $timeIntervalsModel = $this->timeIntervalRepository->get($id);
                $this->messageManager->addError(__('This Time Interval no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'testdelivery/*/edit',
                    [
                        'slider_id' => $timeIntervalsModel->getId(),
                        '_current'  => true
                    ]
                );

                return $resultRedirect;
            }
        }


        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Test_Delivery::timeinterval');
        $resultPage->getConfig()->getTitle()
            ->set(__('Time Interval'))
            ->prepend($timeIntervalsModel->getId() ? $timeIntervalsModel->getName() : __('New Time Intervals'));

        return $resultPage;
    }

    /**
     * @return \Test\Delivery\Api\Data\TimeIntervalInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initSlider()
    {
        $id = (int)$this->getRequest()->getParam('time_interval_id');

        $model = $this->timeIntervalFactory->create();
        if ($id) {
            $model = $this->timeIntervalRepository->get($id);
//            $model = $model->load($id);
        }

        $this->coreRegistry->register('testdelivery_timeinterval', $model);

        return $model;
    }
}
