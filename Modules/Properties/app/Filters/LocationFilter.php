<?php
namespace Modules\Properties\app\Filters;

use Laravel\Scout\Builder;

class LocationFilter implements FilterStrategyInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('location', $value);
    }
}

