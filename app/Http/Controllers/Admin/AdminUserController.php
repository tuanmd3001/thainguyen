<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdminUserDataTable;
use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Models\Admin\AdminUser;
use App\Models\Constants;
use App\Repositories\AdminUserRepository;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository;
use Flash;
use Response;

class AdminUserController extends AppBaseController
{
    /** @var  AdminUserRepository */
    private $userRepository;
    private $defaultPassword;

    public function __construct(AdminUserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param AdminUserDataTable $adminUserDataTable
     * @return Response
     */
    public function index(AdminUserDataTable $adminUserDataTable)
    {
        return $adminUserDataTable->render('admin.admin_users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'admins')->get();
        $permissions = Permission::where('guard_name', 'admins')->get();
        return view('admin.admin_users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateAdminUserRequest $request
     *
     * @return Response
     */
    public function store(CreateAdminUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make(Constants::DEFAULT_PASSWORD);

        $user = $this->userRepository->create($input);

        if (!empty($input['roles'])){
            $user->syncRoles($input['roles']);
        }
        else {
            $user->syncRoles([]);
        }

        if (!empty($input['permissions'])){
            $user->syncPermissions($input['permissions']);
        }
        else {
            $user->syncPermissions([]);
        }


        Flash::success('Thêm mới thành công');

        return redirect(route('admin.adminUsers.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        if ($user->username == 'admin') {
            return redirect(route('admin.adminUsers.index'));
        }
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminUsers.index'));
        }

        $permissions = Permission::where('guard_name', 'admins')->get();
        $userPermissions = $user->permissions()->pluck('name')->toArray();

        return view('admin.admin_users.show', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        if ($user->username == 'admin') {
            return redirect(route('admin.adminUsers.index'));
        }
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminUsers.index'));
        }

        $roles = Role::where('guard_name', 'admins')->get();
        $permissions = Permission::where('guard_name', 'admins')->get();
        $userPermissions = $user->permissions()->pluck('name')->toArray();

        return view('admin.admin_users.edit', compact('user', 'roles', 'permissions', 'userPermissions'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateAdminUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdminUserRequest $request)
    {
        $user = $this->userRepository->find($id);
        if ($user->username == 'admin') {
            return redirect(route('admin.adminUsers.index'));
        }
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminUsers.index'));
        }
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }
        $user = $this->userRepository->update($input, $id);

        if (!empty($input['roles'])){
            $user->syncRoles($input['roles']);
        }
        else {
            $user->syncRoles([]);
        }

        if (!empty($input['permissions'])){
            $user->syncPermissions($input['permissions']);
        }
        else {
            $user->syncPermissions([]);
        }

        Flash::success('Cập nhật thông tin thành công');

        return redirect(route('admin.adminUsers.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);
        if ($user->username == 'admin') {
            return redirect(route('admin.adminUsers.index'));
        }
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminUsers.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Xóa thành công');

        return redirect(route('admin.adminUsers.index'));
    }

    public function reset_password($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.adminUsers.index'));
        }
        $user->password = Hash::make(Constants::DEFAULT_PASSWORD);
        $this->userRepository->update($user->toArray(), $id);
        Flash::success('Mật khẩu đã được đặt về mặc định.');
        return redirect(route('admin.adminUsers.show', $id));
    }

    public function change_password(Request $request){
        if($request->isMethod('get')){
            return view('admin.admin_users.change_password');
        }
        elseif($request->isMethod('post')) {
            $request->validate( [
                'old_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                're_new_password' => ['same:new_password']
            ]);

            AdminUser::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

            Flash::success('Đổi mật khẩu thành công.');
            return redirect(route('admin.home'));
        }
        else {
            return redirect(route('admin.home'));
        }

    }
}
