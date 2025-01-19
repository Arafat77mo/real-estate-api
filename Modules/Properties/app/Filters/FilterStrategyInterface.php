<?php

namespace Modules\Properties\app\Filters;
use Laravel\Scout\Builder;

interface FilterStrategyInterface
{
    public function apply(Builder $query, $value): void;

}
