<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdminPermissionDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdateAdminPermissionRequest;
use App\Repositories\PermissionRepository;
use Flash;
use Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionController extends AppBaseController
{
    /** @var  PermissionRepository */
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepository = $permissionRepo;
    }

    /**
     * Display a listing of the Permission.
     *
     * @param AdminPermissionDataTable $permissionDataTable
     * @return Response
     */
    public function index(AdminPermissionDataTable $permissionDataTable)
    {
        return $permissionDataTable->render('admin.admin_permissions.index');
    }

    /**
     * Show the form for editing the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminPermissions.index'));
        }

        return view('admin.admin_permissions.edit')->with('permission', $permission);
    }

    /**
     * Update the specified Permission in storage.
     *
     * @param  int              $id
     * @param UpdateAdminPermissionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdminPermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminPermissions.index'));
        }

        $permission = $this->permissionRepository->update($request->all(), $id);

        Flash::success('Cập nhật thông tin thành công');

        return redirect(route('admin.adminPermissions.index'));
    }

    protected function extractController($name)
    {
        $filename = explode('.php', $name);
        if (count(explode('Controller.php', $name)) > 1) {
            # code...
            if (count($filename) > 1) {
                return $filename[0];
            }
        }

        return false;
    }

    public function firstRun(){
        $exceptRoute = [
            'api',
            'login',
            'logout',
            'roles',
            'permissions',
            'firstRun',
        ];
        $listRoutes = [];

        foreach (\Route::getRoutes()->getRoutes() as $route)
        {
            $uri = $route->uri();
            if (strpos($uri, 'ignition') == false){
                $routeName = explode('/', $uri)[0];
                if ($routeName != "" && !in_array($routeName, $listRoutes) && !in_array($routeName, $exceptRoute)){
                    $listRoutes[] = $routeName;
                }
            }
        }
        $permissions = collect($listRoutes)->map(function ($permission) {
            return ['name' => $permission, 'display_name' => $permission, 'guard_name' => 'web'];
        });
        Permission::query()->truncate();
        Permission::insert($permissions->toArray());
        $role = Role::where('name', 'SuperAdmin')->first();
        if ($role){
            $role->givePermissionTo(Permission::all());
        }
        return redirect('/');
    }
}
