<?php
namespace Kakarazi\Businessrss\Block;

use Magento\Framework\View\Element\Template;
use Kakarazi\Businessrss\Helper\Data;

class Businessrss extends Template
{
    protected $businessrssHelper;

    public function __construct(
        Template\Context $context,
        Data $businessrssHelper,
        array $data = []
    ) {
        $this->businessrssHelper = $businessrssHelper;
        parent::__construct($context, $data);
    }

    public function getRssItems($currentPage = 1)
    {
        $itemsPerPage = 5;  // Số lượng bài mỗi trang

        // Lấy dữ liệu RSS
        $rssData = $this->businessrssHelper->getRssFeed(); // Giả sử helper đã trả về dữ liệu RSS dưới dạng một mảng các item

        // Tính tổng số phần tử
        $totalItems = count($rssData);

        // Tính toán phân trang
        $offset = ($currentPage - 1) * $itemsPerPage;  // Tính toán offset cho trang hiện tại
        $rssItems = array_slice($rssData, $offset, $itemsPerPage);  // Cắt mảng để lấy dữ liệu cho trang hiện tại

        return $rssItems;
    }

    // Phương thức lấy tổng số bài
    public function getTotalItems()
    {
        $rssData = $this->businessrssHelper->getRssFeed();
        return count($rssData);  // Trả về tổng số bài báo từ RSS feed
    }
}
