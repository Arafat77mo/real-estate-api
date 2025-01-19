<?php

namespace Modules\Properties\app\Filters;

use Laravel\Scout\Builder;

class MaxPriceFilter implements FilterStrategyInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('price', '<=', $value);
    }
}
