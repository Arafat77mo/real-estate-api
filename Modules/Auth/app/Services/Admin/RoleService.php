<?php

namespace Modules\Auth\app\Services\Admin;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Create a new role along with permissions.
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data)
    {
            $role = Role::create(['name' => $data['name']]);

            // إضافة الأذونات إذا كانت موجودة
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']); // إضافة الأذونات
            }

            return $role;

    }


    /**
     * Update an existing role along with permissions.
     *
     * @param int $roleId
     * @param array $data
     * @return Role
     */
    public function updateRole(int $roleId, array $data)
    {

            // العثور على الدور
            $role = Role::findOrFail($roleId);

            // تحديث اسم الدور
            $role->update(['name' => $data['name']]);

            // تحديث الأذونات
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']); // تحديث الأذونات
            }

            return $role;

    }

    /**
     * Delete a role.
     *
     * @param int $roleId
     * @return bool
     */
    public function deleteRole(int $roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            return $role->delete();
        } catch (Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            throw new Exception('Role deletion failed');
        }
    }

    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAllRoles()
    {
        return Role::all();
    }
}
