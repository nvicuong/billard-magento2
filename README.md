# Billard Shop - Billiard Supply Online Store (Magento 2)

Billard Shop is an e-commerce platform built on **Magento 2**, designed for selling billiard supplies online. This repository provides a **Dockerized** environment for easy deployment and management.

## Author
- Nguyễn Việt Cường
- Lê Tiến Thực

## Features
- **Fully functional Magento 2 store** for billiard products.
- **Docker-based setup** for easy deployment.
- **Customizable payment methods** to suit different business needs.
- **Multi-language support** for a broader audience.
- **Performance optimized** with caching and indexing strategies.


## Requirement
- docker version >= 27.3.1
- docker-compose version >= 8.32

## Step to run mock web
- bin/start

## Step to stop mock web
- bin/stop

## bin commands
- bin/start: run Billard Shop
- bin/status: show docker-compose status
- bin/stop: stop Billard Shop
- bin/magento: exec magento commands

## Notes
- At the time of running Billard Shop, certain features such as "Thời tiết" (Weather) and "Tỉ giá tiền tệ" (Currency Exchange Rates) may not function correctly due to API key limitations.
- To enable payment processing, you must configure and provide the necessary payment method information.


![image alt](https://github.com/nvicuong/billard-magento2/blob/main/billard-shop.png?raw=true)
