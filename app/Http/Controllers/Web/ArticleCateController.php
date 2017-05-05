<?php

namespace App\Http\Controllers\Web;

use Aimeos\Shop\Base\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \DB;
use \Request;

class ArticleCateController extends Controller
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

    public function showArticleCate(){
        $result = DB::select('select * from food_info_article_category');
        return view("web/articlecate",["op" => "show", "result" => $result]);

    }
    public function editArticleCate($id){
        $results = DB::select('select * from food_info_article_category where id =?',[$id]);
        $result = "";
        if(!empty($results)){
            $result = $results[0];
        }
        if(Request::ajax()){
            $category_name = trim($_POST["category_name"]);
            if(empty($category_name)){
                return array(
                    "status" => 0,
                    "info" => "分类名称不能为空！"
                );
            }
            if(empty($id)){
                $result = DB::insert('insert into food_info_article_category (category_name) values (?)', [$category_name]);
            }else{
                $result = DB::update('update food_info_article_category set category_name = ? where id = ?', [$category_name,$id]);
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

        return view("web/articlecate",["op" => "edit", "result" => $result]);

    }
    public function delArticleCate($id){
        $results = DB::select('select * from food_info_article_category where id =?',[$id]);
        if(empty($results)){
            return array(
                "status" => 0,
                "info" => "该文章分类不存在！"
            );
        }
        $result = DB::delete('delete from food_info_article_category where id =?',[$id]);
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
