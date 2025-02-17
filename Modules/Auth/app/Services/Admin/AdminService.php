<?php
namespace Modules\Auth\app\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminService
{
    protected function __construct(protected User $user )
    {

    }

    // Register a new user
    public function create($validatedData)
    {
        $user = $this->user::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => $validatedData['type'],
        ]);

        // Assign default role (based on type)
        $user->assignRole($validatedData['role']);

        return $user;
    }

    public function update($userId, $validatedData)
    {
        $user =  $this->user::findOrFail($userId);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
            'type' => $validatedData['type'],

        ]);

            $user->assignRole($validatedData['role']);



        return $user;
    }

    public function delete($userId)
    {
        $user =  $this->user::findOrFail($userId);
        $user->delete();

        return $user;
    }

    public function show($userId)
    {
        return  $this->user::findOrFail($userId);
    }

    public function index()
    {
        $query =  $this->user::search($request['query'] ?? '');
       return   $query->fastPaginate(10000);

    }
}
