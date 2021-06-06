<?php

namespace ScaryLayer\NovaPoshta;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ScaryLayer\NovaPoshta\Repositories\City;
use ScaryLayer\NovaPoshta\Repositories\Warehouse;

class NovaPoshta
{
    /**
     * Get cities list as [Ref => Description] array
     */
    public static function getCities(
        ?string $search = null
    ): Collection {
        return City::get()
            ->search($search)
            ->sort()
            ->pluck();
    }

    /**
     * Get warehouses list as [Ref => Description] array
     */
    public static function getWarehouses(
        string $cityRef,
        ?string $search = null
    ): Collection {
        return Warehouse::get($cityRef)
            ->search($search)
            ->sort()
            ->sort('Number')
            ->pluck();
    }

    /**
     * Get data from stored file
     */
    public static function getFile(string $name): Collection
    {
        $path = config('nova-poshta.path') . '/' . $name;

        $content = File::get($path);
        $json = json_decode($content);

        return collect($json);
    }
}
