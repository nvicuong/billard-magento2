<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Kakarazi\Weather\Model\Weather as WeatherModel;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Kakarazi_Weather::save';

    protected $_coreRegistry;
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Load layout and set active menu
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Kakarazi_Weather::weather');
        return $resultPage;
    }

    public function execute()
    {
        // Get ID and create model
        $id = $this->getRequest()->getParam('city_id');
        $model = $this->_objectManager->create(WeatherModel::class);

        // Initial checking
        if ($id) {
            $model->load($id);

            // If cannot get ID of model, display error message and redirect to List page
            if (!$model->getId()) {
                $this->messageManager->addError(__('This position no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('weather', $model);

        // Build form
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('Add new coordinates'));

        return $resultPage;
    }
}
