<?php
namespace Modules\Properties\app\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveAndNotOwnerScope implements Scope
{
    /**
     * Apply the scope to the given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', 'active');
    }
}
