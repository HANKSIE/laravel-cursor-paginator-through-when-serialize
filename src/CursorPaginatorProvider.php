<?php

namespace Hanksie\CursorPaginator;

use Illuminate\Support\ServiceProvider;

class CursorPaginatorProvider extends ServiceProvider
{
    public function register()
    {
        // replace origin cursor paginator.
        $this->app->bind(\Illuminate\Pagination\CursorPaginator::class, \Hanksie\CursorPaginator\CursorPaginator::class);
    }
}
