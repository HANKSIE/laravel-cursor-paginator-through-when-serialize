<?php

namespace Hanksie\CursorPaginator\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            \Hanksie\CursorPaginator\CursorPaginatorProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_users_table.php';
        (new \CreateUsersTable)->up();
    }
}
