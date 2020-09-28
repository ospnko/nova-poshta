<?php

namespace ScaryLayer\NovaPoshta;

use Illuminate\Support\Facades\Storage;

class NovaPoshta
{
    private static function get($name)
    {
        return collect(
            json_decode(Storage::get("nova-poshta/$name"))
        );
    }

    public static function getCities($search = null)
    {
        $list = $search
            ? self::get('cities.json')
                ->filter(function ($item) use ($search) {
                    return app()->getLocale() == 'ua'
                        ? mb_stripos($item->Description, $search) !== false
                        : mb_stripos($item->DescriptionRu, $search) !== false;
                })
                ->sortBy(function ($item) {
                    return ((int) $item->IsBranch)
                        ? -10000
                        : $item->{self::getNameField()};
                })
            : self::get('cities.json')->sortBy(self::getNameField());

        return $list->pluck(self::getNameField(), 'Ref');
    }

    public static function getWarehouses($city_id, $search = null)
    {
        $list = $search
            ? self::get("warehouses/$city_id.json")
                ->filter(function ($item) use ($search) {
                    return app()->getLocale() == 'ua'
                        ? mb_stripos($item->Description, $search) !== false
                        : mb_stripos($item->DescriptionRu, $search) !== false;
                })
            : self::get("warehouses/$city_id.json");

        return $list
            ->sortBy(self::getNameField())
            ->sortBy('Number')
            ->pluck(self::getNameField(), 'Ref');
    }

    private static function getNameField()
    {
        return app()->getLocale() == 'ua' ? 'Description' : 'DescriptionRu';
    }
}
