# Billard Shop - Billiard supply online store built on magento 2

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
- At the time you start Billard Shop, the features such as "Thời tiết", "Tỉ giá tiền tệ" may no longer work correctly because of the API key.
- You need to add information about payment methods to use these features.


![image alt](https://github.com/nvicuong/billard-magento2/blob/main/billard-shop.png?raw=true)
