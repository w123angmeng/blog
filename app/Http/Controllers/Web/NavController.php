<?php

namespace App\Http\Controllers\Web;

use Aimeos\Shop\Base\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \DB;
use \Request;

class NavController extends Controller
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

    public function showNav(){
        $result = DB::select('select * from food_info_adv');
        return view("web/nav",["op" => "show", "result" => $result]);

    }
    public function editNav($id){
        $results = DB::select('select * from food_info_adv where id =?',[$id]);
        $result = "";
        if(!empty($results)){
            $result = $results[0];
        }
        if(Request::ajax()){
            $advname = trim($_POST["advname"]);
            if(empty($advname)){
                return array(
                    "status" => 0,
                    "info" => "标题不能为空！"
                );
            }
            $data = [$advname, $_POST["link"], $_POST["thumb"], $_POST["displayorder"], $_POST["enabled"]];
            if(empty($id)){
                $result = DB::insert('insert into food_info_adv (advname, link, thumb, displayorder, enabled) values (?, ?, ?, ?, ?)', $data);
            }else{
                $data = [$advname, $_POST["link"], $_POST["thumb"], $_POST["displayorder"], $_POST["enabled"], $id];
                $result = DB::update('update food_info_adv set advname = ?, link = ?, thumb = ?, displayorder = ?, enabled = ? where id = ?', $data);
            }
            if($result){
                return array(
                    "status" => 1,
                    "info" => "更新数据成功！"
                );
            }
            return array(
                "status" => 0,
                "info" => "更新数据失败！"
            );

        }

        return view("web/nav",["op" => "edit", "result" => $result]);

    }
    public function delNav($id){
        $results = DB::select('select * from food_info_adv where id =?',[$id]);
        if(empty($results)){
            return array(
                "status" => 0,
                "info" => "该幻灯片不存在！"
            );
        }
        $result = DB::delete('delete from food_info_adv where id =?',[$id]);
        if($result){
            return array(
                "status" => 1,
                "info" => "更新数据成功！"
            );
        }
        return array(
            "status" => 0,
            "info" => "更新数据失败！"
        );
    }

}
