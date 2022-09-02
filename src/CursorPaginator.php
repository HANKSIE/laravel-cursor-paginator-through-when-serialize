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
     * Register a callback for mapping each item in the slice of items to the "data" field when serialized.
     *
     *
     * @param  callable  $callback
     * @return $this
     */
    public function through(callable $callback)
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
