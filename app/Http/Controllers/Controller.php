<?php

namespace App\Http\Controllers;

use App\Contracts\InteractsWithSearch;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

abstract class Controller
{
    protected int $per_page = 15;

    protected function get(mixed $model, ?callable $action = null): LengthAwarePaginator
    {
        if (
            new $model instanceof InteractsWithSearch
            && ($q = request()->input('q'))
        ) {
            $builder = $model::search($q);
        } else {
            $builder = $model::query();
        }

        if (
            ($sort = request()->input('sort'))
            && isset($sort['direction'])
        ) {
            $direction = $sort['direction'] == 'ascending'
                ? 'asc'
                : 'desc';

            $builder->orderBy(
                Str::snake($sort['column']),
                $direction,
            );
        }

        if (request()->input('withTrashed')) {
            $builder->whereNull('deleted_at');
        }

        if ($action) {
            $action($builder);
        }

        return $builder->paginate($this->per_page);
    }
}
