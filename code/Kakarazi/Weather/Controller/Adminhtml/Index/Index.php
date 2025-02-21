<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Index extends Action
{

    public function execute(): ResultInterface
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('Kakarazi_Weather::weather');
        $page->addBreadcrumb(__('Weather'), __('Weather'));
        $page->addBreadcrumb(__('Manage Weather Api'), __('Manage Weather Api'));
        $page->getConfig()->getTitle()->prepend(__('Weather'));

        return $page;
    }
}
