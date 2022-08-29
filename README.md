[![test](https://github.com/hanksie/laravel-cursor-paginator-through-when-serialize/actions/workflows/test.yml/badge.svg)](https://github.com/HANKSIE/laravel-cursor-paginator-through-when-serialize/actions)

### Why make the package:

When using `AbstractCursorPaginator::through()` or `AbstractCursorPaginator::getCollection()->transform()` to transform each item, and serializing
(e.g. toArray(), toJson()) implicitly or obviously will give an error: `Undefined index`,
Paginate and SimplePaginate don't have this problem.

This error occurs in `AbstractCursorPaginator::getParametersForItem()`:

https://github.com/laravel/framework/blob/bdc707f8b9bcad289b24cd182d98ec7480ac4491/src/Illuminate/Pagination/AbstractCursorPaginator.php#L218

When pagination has previous page or next page, the method will be called. The error occurs because AbstractCursorPaginator need a field to get the page cursor. For example, we have a user model, it contain 'id' and 'name' field. We call User::cursorPaginate(n) to get a CursorPaginator instance, when the paginator serialized, `AbstractCursorPaginator::getParametersForItem()` be called. Please see source link above, $item is a user model instance like
`{ id: 1, name: 'someone', ...}`, $parameterName in callback block is `"user.id"`, the result is 'id':

```php
$item[$parameterName] ?? $item[Str::afterLast($parameterName, '.')]
// $item doesn't have key named 'user.id', so run next, get the user's id
```

If transforming paginate items, because cannot access exist key, we will give an error: `Undefined index: id`,

```php
// $item: {extra_field: '...', origin: [user instance]}
$item[$parameterName] ?? $item[Str::afterLast($parameterName, '.')]
// $item doesn't have key named 'user.id', so run next, but $item doesn't have key named 'id' either. give an error: `Undefined index: id`
```

### How the package fix it:

Using another function for transform/mapping data we want, when serializing the function be called, Because it not changed origin $items, it can avoid Undefined index error.

### How to use:

```php
User::factory(10)->create();
$cursorPaginate = User::cursorPaginate(5);

// register mapping item callback
$cursorPaginate->throughWhenSerialize(
    /*
     * The callback called by Illuminate\Support\Collection::map
     * when serializing for filling 'data' field
     */
    function ($item) {
        return ['extra_field' => '...', 'origin' => $item];
    }
);

/**
 * $cursorPaginate->toArray() or $cursorPaginate->toJson()
 *
 * Call transformCallback when serialize
 * /
```
