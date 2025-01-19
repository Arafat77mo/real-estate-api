<?php
namespace Modules\Properties\app\Filters;

use Laravel\Scout\Builder;



class TypeFilter implements FilterStrategyInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('type', $value);
    }
}

