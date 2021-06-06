This package provides a command for pre-loading Nova Poshta warehouses and class for working with it.

## Installation

1. Install package by `composer require scary-layer/nova-poshta`.
1. Add command `php artisan nova-poshta:load` to crontab.

Documentation of Nova Poshta API recommends to do it daily.

If you just started development, run this command single time to warehouses to be loaded.

## Using
Use `ScaryLayer\NovaPoshta\NovaPoshta` class to work with loaded data.
Currently it contain methods:
- `getCities` - to get array of available cities like [$cityRef => $cityDescription]
- `getWarehouses` - to get array of available warehouses like [$warehouseRef => $warehouseDescription]

Or you may use `ScaryLayer\NovaPoshta\Repositories\City` and `ScaryLayer\NovaPoshta\Repositories\Warehouse` to more advanced work with stored data.
