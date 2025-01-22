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
            $role = Role::create([
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

            DB::commit(); // حفظ التغييرات في قاعدة البيانات
            return $role;

        } catch (\Exception $e) {
            DB::rollBack(); // إلغاء جميع العمليات
            throw $e; // إرسال الخطأ للاستفادة منه
        }
    }

    public function updateRole(int $roleId, array $data)
    {
        DB::beginTransaction();

        try {
            // العثور على الدور
            $role = Role::findOrFail($roleId);

            // تحديث اسم الدور
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

            DB::commit(); // حفظ التغييرات في قاعدة البيانات
            return $role;

        } catch (\Exception $e) {
            DB::rollBack(); // إلغاء جميع العمليات
            throw $e; // إرسال الخطأ للاستفادة منه
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
        return Role::fastPaginate(50);
    }
}
