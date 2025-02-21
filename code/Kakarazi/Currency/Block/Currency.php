<?php
namespace Kakarazi\Currency\Block;

use Magento\Framework\View\Element\Template;
use Kakarazi\Currency\Helper\Data;

class Currency extends Template
{
    protected $currencyHelper;

    public function __construct(
        Template\Context $context,
        Data $currencyHelper,
        array $data = []
    ) {
        $this->currencyHelper = $currencyHelper;
        parent::__construct($context, $data);
    }

    public function getCurrencyRates()
    {
        // Gọi helper để lấy tỷ giá tiền tệ từ API
        return $this->currencyHelper->getCurrencyFromAPI();
    }
}
