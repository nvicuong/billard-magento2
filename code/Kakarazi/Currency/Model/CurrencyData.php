<?php
namespace Kakarazi\Currency\Model;

use Magento\Framework\Model\AbstractModel;

class CurrencyData extends AbstractModel
{
    protected $_idFieldName = 'id';  // Trường chính là 'id'
    protected $_table = 'kakarazi_currency_data';  // Tên bảng
    protected $_model = 'Kakarazi\Currency\Model\ResourceModel\CurrencyData';  // Resource model

    protected function _construct()
    {
        $this->_init('Kakarazi\Currency\Model\ResourceModel\CurrencyData');
    }
}
