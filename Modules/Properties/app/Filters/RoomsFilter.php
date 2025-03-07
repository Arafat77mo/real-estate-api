<?php
namespace Modules\Properties\app\Filters;

use Laravel\Scout\Builder;


class RoomsFilter implements FilterStrategyInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('rooms', $value);
    }
}

