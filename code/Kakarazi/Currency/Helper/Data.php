<?php
namespace Kakarazi\Currency\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;
use Kakarazi\Currency\Model\CurrencyDataFactory;

class Data extends AbstractHelper
{
    protected $curl;
    protected $currencyDataFactory;

    // Thêm constructor để inject model factory
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Curl $curl,
        CurrencyDataFactory $currencyDataFactory
    ) {
        $this->curl = $curl;
        $this->currencyDataFactory = $currencyDataFactory;
        parent::__construct($context);
    }

    // Kiểm tra và lấy dữ liệu từ bảng (chỉ lấy 1 bản ghi duy nhất)
    public function getCurrencyFromDatabase()
    {
        $currencyData = $this->currencyDataFactory->create();
        $connection = $currencyData->getResource()->getConnection();

        // Query để lấy ID và nội dung bản ghi
        $select = $connection->select()
            ->from($currencyData->getResource()->getMainTable(), ['id', 'content']) // Lấy ID và content
            ->order('id DESC')
            ->limit(1);

        $result = $connection->fetchRow($select);

        return $result ? ['id' => $result['id'], 'content' => json_decode($result['content'], true)] : null;
    }


    // Kiểm tra nếu có bản ghi hợp lệ, nếu không gọi API và lưu vào bảng
    public function getCurrencyRates()
    {
//         Kiểm tra dữ liệu trong bảng
        $data = $this->getCurrencyFromDatabase();

        if ($data) {
            // Dữ liệu đã có, kiểm tra xem ngày trong content có phải là ngày hôm nay không
            $today = date('Y-m-d');
            if (isset($data['content']['date']) && $data['content']['date'] == $today) {
                return $data['content']['rates'];  // Nếu đúng ngày hôm nay, trả về dữ liệu tỷ giá
            }
        }

        $data = $this->getCurrencyFromAPI();

        // Nếu không có dữ liệu hợp lệ hoặc ngày không phải là hôm nay, gọi API và lưu lại
        return $data;
    }

    // Đọc dữ liệu tỷ giá từ API và lưu vào bảng
    public function getCurrencyFromAPI()
    {
        $url = "https://data.fixer.io/api/latest?access_key=43e50ef66747030e569112513a91d8f6"; // API URL

        try {
            // Gửi request và nhận phản hồi
            $this->curl->get($url);
            $response = $this->curl->getBody();
            $data = json_decode($response, true);
//            return $data;

            // Kiểm tra nếu API trả về thành công
            if ($data['success']) {
                // Lưu vào bảng (chỉ có 1 bản ghi duy nhất)
                $this->saveCurrencyToDatabase($data);
//                return $data;
                return $data['rates']; // Trả về danh sách tỷ giá
            } else {
                return null; // Trả về null nếu không thành công
            }
        } catch (\Exception $e) {
            // Xử lý lỗi
            return null;
        }
    }

    // Lưu dữ liệu vào bảng
    public function saveCurrencyToDatabase($data)
    {
        // Lấy model từ factory
        $currencyData = $this->currencyDataFactory->create();

        // Lấy dữ liệu hiện tại từ database
        $existingData = $this->getCurrencyFromDatabase();

        if ($existingData) {
            // Nếu đã có bản ghi, load bản ghi đó bằng ID
            $currencyData->load($existingData['id']);
        }

        // Cập nhật hoặc tạo mới nội dung JSON
        $currencyData->setData('content', json_encode($data));

        try {
            $currencyData->save(); // Lưu vào bảng
        } catch (\Exception $e) {
            // Log lỗi nếu xảy ra
            $this->logger->error('Error saving currency data: ' . $e->getMessage());
            throw $e;
        }
    }
}

