<?php

namespace Kakarazi\Weather\Model\Weather;

use Kakarazi\Weather\Model\ResourceModel\Weather\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;
    protected $dataPersistor;
    protected $loadedData;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $weatherCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $weatherCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     */
    public function getData()
    {
        // Get items
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $city) {
            $data = $city->getData();

            $this->loadedData[$city->getId()] = $data;
        }

        $data = $this->dataPersistor->get('kakarazi_weather');
        if (!empty($data)) {
            $weather = $this->collection->getNewEmptyItem();
            $weather->setData($data);
            $this->loadedData[$weather->getId()] = $weather->getData();

            $this->dataPersistor->clear('kakarazi_weather');
        }

        return $this->loadedData;
    }
}
