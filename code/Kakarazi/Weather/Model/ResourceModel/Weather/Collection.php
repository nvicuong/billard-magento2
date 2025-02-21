<?php declare(strict_types=1);

namespace Kakarazi\Weather\Model\ResourceModel\Weather;

use Kakarazi\Weather\Model\ResourceModel\Weather as WeatherResource;
use Kakarazi\Weather\Model\Weather as WeatherModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct() {
        $this->_init(
            WeatherModel::class,
            WeatherResource::class
        );
    }
}
