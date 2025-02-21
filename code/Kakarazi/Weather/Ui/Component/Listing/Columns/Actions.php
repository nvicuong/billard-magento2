<?php

namespace Kakarazi\Weather\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

/**
 * Class PageActions
 */
class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path
     */
    const WEATHER_URL_PATH_EDIT = 'weather/index/edit';
    const WEATHER_URL_PATH_DELETE = 'weather/index/delete';

    protected $urlBuilder;

    private $editUrl;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::WEATHER_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            // Get column name
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['city_id'])) {
                    // Add Edit link
                    $item[$fieldName]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['city_id' => $item['city_id']]),
                        'label' => __('Edit')
                    ];

                    // Add Delete link
                    $item[$fieldName]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::WEATHER_URL_PATH_DELETE, ['city_id' => $item['city_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' =>  __('Delete %1', $item['name']),
                            'message' => __('Are you sure you want to delete this record?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
