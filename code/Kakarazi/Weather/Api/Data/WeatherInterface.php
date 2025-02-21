<?php declare(strict_types=1);

namespace Kakarazi\Weather\Api\Data;

interface WeatherInterface
{
    public function getCityId(): int;
    public function getName(): string;
    public function setName(string $name);
    public function getCountry(): string;
    public function setCountry(string $country);
    public function getLongitude(): float;
    public function setLongitude(float $longitude);
    public function getLatitude(): float;
    public function setLatitude(float $latitude);

    public function getCreatedAt(): string;
    public function setCreatedAt(string $popupCreatedAt);
    public function getUpdatedAt(): string;
    public function setUpdatedAt(string $popupUpdatedAt);
}
