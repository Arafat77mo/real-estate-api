<?php

namespace Modules\Properties\App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Properties\App\Models\Property;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    protected  Property $property;
    public function __construct( Property $property)
    {

    }

    public function delete(User $user, Property $property)
    {
        $user = auth()->user(); // Ensure you're getting the currently authenticated user

        return $user->id === $property->user_id; // التأكد أن المستخدم هو صاحب العقار
    }
}
