<?php

namespace ScaryLayer\NovaPoshta\Repositories;

use Illuminate\Support\Collection;
use ScaryLayer\NovaPoshta\NovaPoshta;

class City
{
    /**
     * Create new City instance
     */
    public function __construct(
        protected Collection $cities
    ) {
        //
    }

    /**
     * Get list of available cities
     */
    public static function get(): self
    {
        $cities = NovaPoshta::getFile('cities.json');

        return new self($cities);
    }

    /**
     * Get list of cities as laravel collection
     */
    public function collect(): Collection
    {
        return $this->cities;
    }

    /**
     * Get the key value pairs of given fields
     * By default it will pluck by Description field in app language
     */
    public function pluck(
        ?string $valueField = null,
        ?string $keyField = 'Ref'
    ): Collection {
        $valueField ??= self::getDefaultField();

        return $this->cities->pluck($valueField, $keyField);
    }

    /**
     * Search cities by given field
     * By default it will be seek by Description field in app language
     */
    public function search(?string $search = null, ?string $field = null): self
    {
        $field ??= self::getDefaultField();

        $this->cities = $this->cities
            ->filter(function ($item) use ($field, $search) {
                return mb_stripos($item->$field, $search) !== false;
            });

        return $this;
    }

    /**
     * Sort cities by the given field
     * By default it will sort by Description field in app language
     */
    public function sort(
        ?string $field = null,
        ?bool $branchesDownBelow = true
    ): self {
        $field ??= self::getDefaultField();

        $this->cities = $this->cities
            ->sortBy(function ($item) use ($field, $branchesDownBelow) {
                return $branchesDownBelow && ((int) $item->IsBranch)
                    ? -10000
                    : $item->$field;
            });

        return $this;
    }

    /**
     * Get default field for operations
     */
    protected static function getDefaultField(?string $lang = null): string
    {
        $lang ??= app()->getLocale();

        return $lang == 'ru' ? 'DescriptionRu' : 'Description';
    }
}