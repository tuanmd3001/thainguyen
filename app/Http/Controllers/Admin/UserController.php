<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Admin\AdminUser;
use App\Models\Constants;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository;
use Flash;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')->get();
        $permissions = Permission::where('guard_name', 'web')->get();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = Str::random(60);

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

        return redirect(route('admin.users.index'));
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
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.users.index'));
        }

        $permissions = Permission::where('guard_name', 'web')->get();
        $userPermissions = $user->permissions()->pluck('name')->toArray();

        return view('admin.users.show', compact('user', 'permissions', 'userPermissions'));
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
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.users.index'));
        }

        $roles = Role::where('guard_name', 'web')->get();
        $permissions = Permission::where('guard_name', 'web')->get();
        $userPermissions = $user->permissions()->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userPermissions'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.users.index'));
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

        return redirect(route('admin.users.index'));
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
        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Xóa thành công');

        return redirect(route('admin.users.index'));
    }

    public function reset_password($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.users.index'));
        }
        $user->password = Hash::make(Constants::DEFAULT_PASSWORD);
        $this->userRepository->update($user->toArray(), $id);
        Flash::success('Mật khẩu đã được đặt về mặc định.');
        return redirect(route('admin.users.show', $id));
    }
}
