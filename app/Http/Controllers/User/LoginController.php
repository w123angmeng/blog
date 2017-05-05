<?php

namespace App\Http\Controllers\User;

use Aimeos\Shop\Base\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \DB;
use \Request;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLogin(){
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            if(empty($username) || empty($password)){
                return array(
                    "status" => 0,
                    "info"   => "用户名不能为空！"
                );
            }
            $users = DB::select('select * from food_info_users where username = :username', ['username' => $username]);
            if(empty($users)){
                return array(
                    "status" => 0,
                    "info" => "该用户不存在！"
                );
            }
            $user = $users[0];

            $encrypt_psd = md5($password . $user->salt);
            if ($user->password === $encrypt_psd){
                return array(
                    "status" => 1,
                    "info" => "登陆成功！"
                );
            }else{
                return array(
                    "status" => 0,
                    "info" => "密码错误！"
                );
            }

    }

}
