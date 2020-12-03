<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Models\Constants;
use App\Models\User;
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

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function change_password(Request $request){
        if($request->isMethod('get')){
            return view('users.change_password');
        }
        elseif($request->isMethod('post')) {
            $request->validate( [
                'old_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                're_new_password' => ['same:new_password']
            ]);

            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

            Flash::success('Đổi mật khẩu thành công.');
            return redirect(route('home'));
        }
        else {
            return redirect(route('home'));
        }

    }
}
