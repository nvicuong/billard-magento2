<?php

namespace Kakarazi\Weather\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $weatherFactory;

    public function __construct(
        Context $context,
        \Kakarazi\Weather\Model\WeatherFactory $weatherFactory
    )
    {
        $this->context = $context;
        $this->weatherFactory = $weatherFactory;
    }

    /**
     * Return Banner ID
     */
    public function getCityId()
    {
        $id = $this->context->getRequest()->getParam('id');
        $city = $this->weatherFactory->create()->load($id);
        if ($city->getId())
            return $id;
        return null;
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
