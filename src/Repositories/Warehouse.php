<?php

namespace ScaryLayer\NovaPoshta\Repositories;

use Illuminate\Support\Collection;
use ScaryLayer\NovaPoshta\NovaPoshta;

class Warehouse
{
    /**
     * Create new Warehouse instance
     */
    public function __construct(
        protected Collection $warehouses
    ) {
        //
    }

    /**
     * Get list of available warehouses by given city Ref
     */
    public static function get(string $cityRef): self
    {
        $warehouses = NovaPoshta::getFile("warehouses/$cityRef.json");

        return new self($warehouses);
    }

    /**
     * Get list of warehouses as laravel collection
     */
    public function collect(): Collection
    {
        return $this->warehouses;
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

        return $this->warehouses->pluck($valueField, $keyField);
    }

    /**
     * Search cities by given field
     * By default it will be seek by Description field in app language
     */
    public function search(?string $search = null, ?string $field = null): self
    {
        $field ??= self::getDefaultField();

        $this->warehouses = $this->warehouses
            ->filter(function ($item) use ($field, $search) {
                return mb_stripos($item->$field, $search) !== false;
            });

        return $this;
    }

    /**
     * Sort cities by the given field
     * By default it will sort by Description field in app language
     */
    public function sort(?string $field = null): self
    {
        $field ??= self::getDefaultField();

        $this->warehouses = $this->warehouses->sortBy($field);
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