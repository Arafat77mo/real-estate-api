<?php
namespace Modules\Auth\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Helpers\ResponseData;
use Modules\Auth\app\Http\Requests\CreateUserRequest;
use Modules\Auth\app\Http\Requests\UpdatePermissionRequest;
use Modules\Auth\app\Http\Requests\UpdateRoleRequest;
use Modules\Auth\app\Services\Admin\AdminService;
use Modules\Auth\app\Services\Admin\PermissionService;
use Modules\Auth\app\Services\Admin\RoleService;
use Modules\Auth\App\Transformers\AuthResource;

class AdminController extends Controller
{
    protected $adminService;
    protected $roleService;
    protected $permissionService;


    public function __construct(AdminService $adminService,RoleService $roleService, PermissionService $permissionService)
    {
        $this->adminService = $adminService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;

    }

    /**
     * Display all users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->adminService->index();
            return ResponseData::send(
                __('messages.success'),
                __('messages.users_fetched'),
                AuthResource::collection($users) // Using AuthResource for user transformation
            );
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.users_fetch_failed')
            );
        }
    }

    /**
     * Show user details.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $user = $this->adminService->show($id);
            return ResponseData::send(
                __('messages.success'),
                __('messages.user_fetched'),
                new AuthResource($user) // Using AuthResource to transform a single user
            );
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.user_not_found')
            );
        }
    }

    /**
     * Create a new user.
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        try {
            // Validation is automatically handled by CreateUserRequest
            $user = $this->adminService->create($request->validated());
            return ResponseData::send(
                __('messages.success'),
                __('messages.user_created'),
                new AuthResource($user) // Transform the newly created user
            );
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.user_creation_failed')
            );
        }
    }

    /**
     * Update user information.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CreateUserRequest $request, $id): JsonResponse
    {


        try {
            $user = $this->adminService->update($id, $request->validated());
            return ResponseData::send(
                __('messages.success'),
                __('messages.user_updated'),
                new AuthResource($user) // Return updated user transformed
            );
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.user_update_failed')
            );
        }
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->adminService->delete($id);
            return ResponseData::send(
                __('messages.success'),
                __('messages.user_deleted')
            );
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.user_deletion_failed')
            );
        }
    }
    /**
     * Create a new role.
     *
     * @param UpdateRoleRequest $request
     * @return JsonResponse
     */
    public function createRole(UpdateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());
            return ResponseData::send('success', __('messages.role_created'), $role);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.role_creation_failed'), $e->getMessage());
        }
    }


    public function getAllRoles( ): JsonResponse
    {
        try {
            $role = $this->roleService->getAllRoles();
            return ResponseData::send('success', __('messages.role_get'), $role);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.role_get_failed'), $e->getMessage());
        }
    }

    /**
     * Update an existing role.
     *
     * @param UpdateRoleRequest $request
     * @param int $roleId
     * @return JsonResponse
     */
    public function updateRole(UpdateRoleRequest $request, int $roleId): JsonResponse
    {
        try {
            $role = $this->roleService->updateRole($roleId, $request->validated());
            return ResponseData::send('success', __('messages.role_updated'), $role);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.role_update_failed'), $e->getMessage());
        }
    }

    /**
     * Delete a role.
     *
     * @param int $roleId
     * @return JsonResponse
     */
    public function deleteRole(int $roleId): JsonResponse
    {
        try {
            $this->roleService->deleteRole($roleId);
            return ResponseData::send('success', __('messages.role_deleted'));
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.role_deletion_failed'), $e->getMessage());
        }
    }

    /**
     * Create a new permission.
     *
     * @param UpdatePermissionRequest $request
     * @return JsonResponse
     */
    public function createPermission(UpdatePermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionService->createPermission($request->validated());
            return ResponseData::send('success', __('messages.permission_created'), $permission);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.permission_creation_failed'), $e->getMessage());
        }
    }

    /**
     * Update an existing permission.
     *
     * @param UpdatePermissionRequest $request
     * @param int $permissionId
     * @return JsonResponse
     */
    public function updatePermission(UpdatePermissionRequest $request, int $permissionId): JsonResponse
    {
        try {
            $permission = $this->permissionService->updatePermission($permissionId, $request->validated());
            return ResponseData::send('success', __('messages.permission_updated'), $permission);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.permission_update_failed'), $e->getMessage());
        }
    }

    /**
     * Delete a permission.
     *
     * @param int $permissionId
     * @return JsonResponse
     */
    public function deletePermission(int $permissionId): JsonResponse
    {
        try {
            $this->permissionService->deletePermission($permissionId);
            return ResponseData::send('success', __('messages.permission_deleted'));
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.permission_deletion_failed'), $e->getMessage());
        }
    }

    public function getAllPermissions( )
    {
        try {
           $all= $this->permissionService->getAllPermissions();
            return ResponseData::send('success', __('messages.permission_get'),$all);
        } catch (\Exception $e) {
            return ResponseData::send('error', __('messages.permission_get_failed'), $e->getMessage());
        }
    }

}
