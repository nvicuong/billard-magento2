<?php

namespace Kakarazi\Weather\Controller\Adminhtml\Index;

use Kakarazi\Weather\Controller\Adminhtml\Index\PostDataProcessor;
use Kakarazi\Weather\Model\Weather as WeatherModel;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Kakarazi\Weather\Helper\Data as WeatherHelper;


class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Kakarazi_Weather::save';

    protected $weatherHelper;

    protected $dataProcessor;
    protected $dataPersistor;

    /**
     * @param Action\Context $context
     * @param \Kakarazi\Weather\Controller\Adminhtml\Index\PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        WeatherHelper $weatherHelper
    )
    {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->weatherHelper = $weatherHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {

            $data['code'] = '100';

            echo json_encode($data);

            // Init model and load by ID if exists
            $model = $this->_objectManager->create(WeatherModel::class);
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            // Validate data
            if (!$this->dataProcessor->validateRequireEntry($data)) {
                // Redirect to Edit page if has error
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }

            $weatherApiResponse = $this->weatherHelper->getWeatherDataByCoordinates($data['longitude'], $data['latitude']);
            $fiveDayForecast = $this->weatherHelper->getFiveDayForecastWeatherDataByCoordinates($data['longitude'], $data['latitude']);


            $model->setUpdatedAt(date('Y-m-d H:i:s'));
            $model->setCountry($data['country']);
            $model->setName($data['name']);
            $model->setLatitude($data['latitude']);
            $model->setLongitude($data['longitude']);


            if (!empty($weatherApiResponse)) {
                $model->setData('code', $weatherApiResponse['cod']);
                $responseJson = json_encode($weatherApiResponse);

                $model->setData('current_weather_description', $responseJson);

            }

            if (!empty($fiveDayForecast)) {
                $responseJson = json_encode($fiveDayForecast);

                $model->setData('five_day_weather_forecast_description', $responseJson);

            }


            // Save data to database
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the city configuration.'));
//                $this->messageManager->addSuccess(__(json_encode($data)));

                $this->dataPersistor->clear('kakarazi_weather');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the city configuration.'));
            }

            $this->dataPersistor->set('kakarazi_weather', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        // Redirect to List page
        return $resultRedirect->setPath('*/*/');
    }
}
