<?php
namespace Modules\Auth\app\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminService
{
    // Register a new user
    public function create($validatedData)
    {
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => $validatedData['type'],
        ]);

        // Assign default role (based on type)
        $role = Role::where('name', $validatedData['type'])->first();
        $user->assignRole($role);

        return $user;
    }

    // Update user information
    public function update($userId, $validatedData)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
        ]);

        // Optionally update the user's role
        if (isset($validatedData['type'])) {
            $role = Role::where('name', $validatedData['type'])->first();
            $user->syncRoles($role);
        }

        return $user;
    }

    // Delete user
    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return $user;
    }

    // Show user details
    public function show($userId)
    {
        return User::findOrFail($userId);
    }

    // Get all users (Index)
    public function index()
    {
        $query = User::search($request['query'] ?? '');
       return   $query->fastPaginate(10000);

    }
}
