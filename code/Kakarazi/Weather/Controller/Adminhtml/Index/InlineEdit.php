<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Kakarazi\Weather\Model\WeatherFactory;


class InlineEdit extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Kakarazi_Weather::save';

    protected $weatherFactory;
    protected $jsonFactory;

    public function __construct(
        Context $context,
        WeatherFactory $weatherFactory,
        JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
        $this->weatherFactory = $weatherFactory;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        // Init result Json
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        // Get POST data
        $postItems = $this->getRequest()->getParam('items', []);
//        var_dump($postItems);
//        die;

        // Check request
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        // Save data to database
        foreach (array_keys($postItems) as $weatherId) {
            try {
                $weather = $this->weatherFactory->create();
                $weather->load($weatherId);
                $weather->setData($postItems[(string)$weatherId]);
                $weather->save();
            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving the image.');
                $error = true;
            }
        }

        // Return result Json
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
