<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "/admin";

    protected $guard = 'admins';

    public function showLoginForm()
    {
        if (Auth::guard($this->guard)->user()){
            return redirect(route('admin.home'));
        }
        return view('admin.auth.login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin_guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if($this->guard()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('admin.login')
                ->withErrors(['error' => 'Thông tin đăng nhập không đúng.']);
        }

    }
    public function logout(Request $request)
    {
        $this->guard()->logout();

//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect($this->redirectTo);
    }
}
