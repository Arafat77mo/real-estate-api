<?php

namespace Modules\Auth\app\Services\Admin;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Create a new permission.
     *
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data)
    {
        try {
            $permission = Permission::create(['name' => $data['name']]);
            return $permission;
        } catch (Exception $e) {
            Log::error('Error creating permission: ' . $e->getMessage());
            throw new Exception('Permission creation failed');
        }
    }

    /**
     * Update an existing permission.
     *
     * @param int $permissionId
     * @param array $data
     * @return Permission
     */
    public function updatePermission(int $permissionId, array $data)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            $permission->update(['name' => $data['name']]);
            return $permission;
        } catch (Exception $e) {
            Log::error('Error updating permission: ' . $e->getMessage());
            throw new Exception('Permission update failed');
        }
    }

    /**
     * Delete a permission.
     *
     * @param int $permissionId
     * @return bool
     */
    public function deletePermission(int $permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            return $permission->delete();
        } catch (Exception $e) {
            Log::error('Error deleting permission: ' . $e->getMessage());
            throw new Exception('Permission deletion failed');
        }
    }

    /**
     * Get all permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions()
    {
        return Permission::all();
    }
}
