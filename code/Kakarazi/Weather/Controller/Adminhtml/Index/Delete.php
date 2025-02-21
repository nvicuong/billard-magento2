<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Kakarazi_Weather::delete';

    public function execute()
    {
        // Get ID of record by param
        $id = $this->getRequest()->getParam('city_id');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // Init model and delete
                $model = $this->_objectManager->create('Kakarazi\Weather\Model\Weather');
                $model->load($id);
                $id = $model->getId();
                $model->delete();

                // Display success message
                $this->messageManager->addSuccess(__('The city has been deleted.'));

                // Redirect to list page
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // Display error message
                $this->messageManager->addError($e->getMessage());
                // Go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        // Display error message
        $this->messageManager->addError(__('We can\'t find the city to delete.'));

        // Redirect to list page
        return $resultRedirect->setPath('*/*/');
    }
}
