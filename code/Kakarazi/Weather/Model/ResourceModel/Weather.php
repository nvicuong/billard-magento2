<?php

namespace Kakarazi\Weather\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Weather extends AbstractDb
{
    private const TABLE_NAME = 'kakarazi_weather';
    private const FIELD_NAME = 'city_id';
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::FIELD_NAME);
    }
}
