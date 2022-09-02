<?php

namespace Hanksie\CursorPaginator\Tests\Feature;

use Hanksie\CursorPaginator\Models\User;
use Hanksie\CursorPaginator\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CursorPaginatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_cursor_paginator_patch_multi_page_passed()
    {

        User::factory(10)->create();
        $cursorPaginate = User::cursorPaginate(5);
        $this->assertEquals(\Hanksie\CursorPaginator\CursorPaginator::class, get_class($cursorPaginate));

        $cursorPaginate->through(function ($item) {
            return ['extra_field' => '...', 'origin' => $item];
        });

        $data = $cursorPaginate->toArray()['data']; // Call transformCallback when serialize
        $this->assertArrayHasKey('origin', $data[0]);
        $this->assertArrayHasKey('extra_field', $data[0]);
    }
}
