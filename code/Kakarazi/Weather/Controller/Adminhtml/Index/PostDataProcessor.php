<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

class PostDataProcessor
{

    protected $messageManager;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
    }

    // Validate required columns
    public function validateRequireEntry(array $data)
    {
        $requiredFields = [
            'longitude' => __('Longitude'),
            'latitude' => __('Latitude'),
        ];
        $errorNo = true;

        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addError(
                    __('"%1" field is required', $requiredFields[$field])
                );
            } else {
                if ($field === 'longitude') {
                    if ($value < -180 || $value > 180) {
                        $errorNo = false;
                        $this->messageManager->addError(
                            __('"%1" must be between -180 and 180', $requiredFields[$field])
                        );
                    }
                }

                if ($field === 'latitude') {
                    if ($value < -90 || $value > 90) {
                        $errorNo = false;
                        $this->messageManager->addError(
                            __('"%1" must be between -90 and 90', $requiredFields[$field])
                        );
                    }
                }
            }
        }
        return $errorNo;
    }
}
