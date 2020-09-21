This package provides a command for pre-loading Nova Poshta warehouses and class for working with it.

## Usage

1. Install package by `composer require scary-layer/nova-poshta`.
1. Add command `php artisan nova-poshta:load` to crontab.  
Documentation of Nova Poshta API recommends to do it daily.  
If you just started development, run this command single time to warehouses be loaded.
1. Use `ScaryLayer\NovaPoshta\NovaPoshta` class to work with loaded data.  
Currently it contain methods:
- `getCities` - to get array of available cities like [$cityId => $cityName]
- `getWarehouses` - to get array of available warehouses like [$warehouseId => $warehouseName]  
If you need to do more advanced work with if, you may just write your own data processor for `storage/app/nova-poshta` json files.
