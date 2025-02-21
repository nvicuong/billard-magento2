<?php
namespace Kakarazi\Currency\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CurrencyData extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('kakarazi_currency_data', 'id');  // Bảng và cột ID
    }
}
