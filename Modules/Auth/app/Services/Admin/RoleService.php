<?php

namespace Modules\Auth\app\Services\Admin;

use Exception;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleService
{

    protected function __construct(protected Role $role)
    {

    }

    /**
     * Create a new role along with permissions.
     *
     * @param array $data
     * @return Role
     */

    public function createRole(array $data)
    {

        DB::beginTransaction();

        try {
            // إنشاء الدور
            $role = $this->role::create([
                'name' => $data['name'],
                'user_id' => auth()->user()->id,
            ]);

            // إضافة الأذونات إذا كانت موجودة
            if (isset($data['permissions'])) {
                if (is_string($data['permissions'])) {
                    $data['permissions'] = explode(',', $data['permissions']);
                }

                $role->permissions()->sync($data['permissions']);
            }

            DB::commit();
            return $role;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRole(int $roleId, array $data)
    {
        DB::beginTransaction();

        try {
            $role = $this->role::findOrFail($roleId);

            $role->update([
                'name' => $data['name'],
                'user_id' => auth()->user()->id,
            ]);

            // تحديث الأذونات إذا كانت موجودة
            if (isset($data['permissions'])) {
                if (is_string($data['permissions'])) {
                    $data['permissions'] = explode(',', $data['permissions']);
                }

                $role->permissions()->sync($data['permissions']);
            }

            DB::commit();
            return $role;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
            $role = $this->role::findOrFail($roleId);
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
        return $this->role::fastPaginate(50);
    }
}
