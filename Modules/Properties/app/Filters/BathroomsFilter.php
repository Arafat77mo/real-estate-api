<?php
namespace Modules\Properties\app\Filters;

use Laravel\Scout\Builder;




class BathroomsFilter implements FilterStrategyInterface
{
    public function apply(Builder $query, $value): void
    {
        $query->where('bathrooms', $value);
    }
}

