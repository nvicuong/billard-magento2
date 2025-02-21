<?php

namespace Kakarazi\Weather\Model;

use Kakarazi\Weather\Api\Data\WeatherInterface;
use Magento\Framework\Model\AbstractModel;
use Kakarazi\Weather\Model\ResourceModel\Weather as WeatherResource;
use Magento\Framework\Api\CustomAttributesDataInterface;

class Weather extends AbstractModel implements WeatherInterface
{
    private const CITY_ID = 'city_id';
    private const NAME = 'name';
    private const CODE = 'code';
    private const COUNTRY = 'country';
    private const LONGITUDE = 'longitude';

    private const LATITUDE = 'latitude';
    private const CREATED_AT = 'created_at';
    private const UPDATED_AT = 'updated_at';

    protected function _beforeSave()
    {
        parent::_beforeSave();

        // Cập nhật trường updated_at
        $this->setUpdatedAt($this->getCurrentDateTime());
    }

    protected function _construct() {
        $this->_eventPrefix = 'kakarazi_weather';
        $this->_eventObject = 'weather';
        $this->_idFieldName = self::CITY_ID;
        $this->_init(WeatherResource::class);
    }

    public function getCityId(): int
    {
        return (int) $this->getData(self::CITY_ID);
    }

    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function getCountry(): string
    {
        return (string) $this->getData(self::COUNTRY);
    }

    public function setCountry(string $country): void
    {
        $this->setData(self::COUNTRY, $country);
    }

    public function getLongitude(): float
    {
        return (string) $this->getData(self::LONGITUDE);
    }

    public function setLongitude(float $longitude): void
    {
        $this->setData(self::LONGITUDE, $longitude);
    }

    public function getLatitude(): float
    {
        return (string) $this->getData(self::LATITUDE);
    }

    public function setLatitude(float $latitude): void
    {
        $this->setData(self::LATITUDE, $latitude);
    }

    public function getCode(): string
    {
        return (string) $this->getData(self::CODE);
    }

    public function setCode(string $code): void
    {
        $this->setData(self::CODE, $code);
    }

    public function getCreatedAt(): string
    {
        return (string) $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt(): string
    {
        return (string) $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

}
