<?php
namespace Hanksie\CursorPaginator;

use Illuminate\Pagination\CursorPaginator as OriginCursorPaginator;

class CursorPaginator extends OriginCursorPaginator
{
    /**
     * @var callable
     */
    protected $throughWhenSerializeCallback = null;

    /**
     *
     * Register $items mapping callback
     *
     * @var callable
     */
    public function throughWhenSerialize(callable $callback)
    {
        $this->throughWhenSerializeCallback = $callback;
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => is_null($this->throughWhenSerializeCallback) ? $this->items->toArray() :
            $this->items->map($this->throughWhenSerializeCallback),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
        ];
    }
}
