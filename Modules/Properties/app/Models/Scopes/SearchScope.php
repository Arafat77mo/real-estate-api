<?php
namespace Modules\Properties\app\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SearchScope implements Scope
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
        // استرجاع البحث من الطلب
        $query = request()->query('query');  // أو يمكن استخدام request()->input('query')

        if ($query) {
            // تطبيق البحث باستخدام Laravel Scout
            $builder->search($query);
        }
    }
}
