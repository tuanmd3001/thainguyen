<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdminRoleDataTable;
use App\DataTables\RoleDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAdminRoleRequest;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateAdminRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\RoleRepository;
use Flash;
use Illuminate\Support\Facades\Auth;
use Response;
use Spatie\Permission\Models\Permission;

class RoleController extends AppBaseController
{
    /** @var  RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the Role.
     *
     * @param RoleDataTable $roleDataTable
     * @return Response
     */
    public function index(RoleDataTable $roleDataTable)
    {
        return $roleDataTable->render('admin.roles.index');
    }

    /**
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param CreateRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();
        $input['guard_name'] = 'admins';
        $role = $this->roleRepository->create($input);
        if (!empty($input['permissions'])){
            $role->syncPermissions($input['permissions']);
        }
        else {
            $role->syncPermissions([]);
        }

        Flash::success('Thêm mới thành công');

        return redirect(route('admin.roles.index'));
    }

    /**
     * Display the specified Role.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.roles.index'));
        }
        $permissions = Permission::where('guard_name', 'web')->get();
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        return view('admin.roles.show', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Show the form for editing the specified Role.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.roles.index'));
        }
        $permissions = Permission::where('guard_name', 'web')->get();
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified Role in storage.
     *
     * @param int $id
     * @param UpdateRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleRequest $request)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.roles.index'));
        }
        $input = $request->all();
        $input['guard_name'] = 'admins';

        $role = $this->roleRepository->update($input, $id);

        if (!empty($input['permissions'])){
            $role->syncPermissions($input['permissions']);
        }
        else {
            $role->syncPermissions([]);
        }

        Flash::success('Cập nhật thông tin thành công');

        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified Role from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.roles.index'));
        }

        $this->roleRepository->delete($id);

        Flash::success('Xóa thành công');

        return redirect(route('admin.roles.index'));
    }
}
