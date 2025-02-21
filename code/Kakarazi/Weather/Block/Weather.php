<?php
namespace Kakarazi\Weather\Block;

use Kakarazi\Weather\Helper\Data;
use Magento\Framework\View\Element\Template;
use Kakarazi\Weather\Model\ResourceModel\Weather\CollectionFactory;

class Weather extends Template
{
    protected $weatherHelper;
    protected $weatherCollectionFactory;

    public function __construct(
        Template\Context $context,
        Data $weatherHelper,
        CollectionFactory $weatherCollectionFactory,
        array $data = []
    ) {
        $this->weatherHelper = $weatherHelper;
        $this->weatherCollectionFactory = $weatherCollectionFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function updateWeatherAction()
    {
        // Lấy tên thành phố từ yêu cầu AJAX
        $city = json_decode(file_get_contents('php://input'), true)['city'];

        // Lấy dữ liệu thời tiết cho thành phố
        $weather = $this->getWeather($city);

        // Kiểm tra xem dữ liệu thời tiết có hợp lệ không
        if ($weather) {
            try {
                // Bắt đầu output buffer để lưu HTML vào biến
                ob_start();

                // Đảm bảo đường dẫn tới template là chính xác
                include ''; // Thay bằng đường dẫn thực tế tới template

                // Lưu nội dung đã render vào biến
                $weatherHTML = ob_get_clean();

                // Trả về dữ liệu dưới dạng JSON
                echo json_encode(['success' => true, 'weatherHTML' => $weatherHTML]);
            } catch (\Exception $e) {
                // Nếu có lỗi khi render template, ghi lại log và trả về lỗi
                $this->logger->error('Error rendering weather template: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error rendering weather data']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Weather data not found']);
        }
    }




    public function getWeather($city = null)
    {
        if (!$city) {
            // Lấy thành phố đầu tiên từ bảng weather_data
            $collection = $this->weatherCollectionFactory->create();
            $collection->setPageSize(1); // Giới hạn lấy 1 bản ghi
            $collection->setCurPage(1); // Lấy trang đầu tiên
            if ($collection->getSize() == 0) {
                return null; // Nếu không có bản ghi nào, trả về null
            }
            $city = $collection->getFirstItem()->getName(); // Lấy tên thành phố của bản ghi đầu tiên
        }

        $collection = $this->weatherCollectionFactory->create();
        $collection->addFieldToFilter('name', ['eq' => $city]);

        $weatherData = $collection->getFirstItem();

        if ($weatherData->getId()) {
            $updatedAt = $weatherData->getData('updated_at');
            $updatedAtTimestamp = strtotime($updatedAt);
            $currentTimestamp = time();

            if (($currentTimestamp - $updatedAtTimestamp) > 300) {
                $longitude = $weatherData->getData('longitude');
                $latitude = $weatherData->getData('latitude');

                $weatherApiResponse = $this->weatherHelper->getWeatherDataByCoordinates($longitude, $latitude);
                $fiveDayForecast = $this->weatherHelper->getFiveDayForecastWeatherDataByCoordinates($longitude, $latitude);

                if (!empty($weatherApiResponse)) {
                    $weatherData->setData('code', $weatherApiResponse['cod']);
                    $responseJson = json_encode($weatherApiResponse);

                    $weatherData->setData('current_weather_description', $responseJson);

                }

                if (!empty($fiveDayForecast)) {
                    $responseJson = json_encode($fiveDayForecast);

                    $weatherData->setData('five_day_weather_forecast_description', $responseJson);

                }

                $weatherData->setData('updated_at', date('Y-m-d H:i:s')); // Cập nhật thời gian hiện tại
                $weatherData->save();
            }

            return $weatherData->getData();
        }

        return null;
    }

    public function getCitiesWithCountries()
    {
        $collection = $this->weatherCollectionFactory->create();
        $collection->getSelect()->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns(['name', 'country']) // Lấy tên thành phố và quốc gia
            ->distinct(true); // Đảm bảo không trùng lặp

        // Trả về danh sách thành phố và quốc gia dưới dạng mảng
        $citiesWithCountries = [];
        foreach ($collection as $city) {
            $citiesWithCountries[] = [
                'name' => $city->getName(),
                'country' => $city->getCountry()  // Lấy quốc gia
            ];
        }

        return $citiesWithCountries;
    }





    public function getWeatherDataFromDb()
    {
        $collection = $this->weatherCollectionFactory->create();
        return $collection->getItems(); // Trả về danh sách các item từ bảng `kakarazi_weather`.
    }
}
