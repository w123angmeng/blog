<?php

namespace App\Http\Controllers\Web;

use Aimeos\Shop\Base\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \DB;
use \Request;

class ArticleController extends Controller
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

    public function showArticle(){
        $result = DB::select('select * from food_info_article');
        return view("web/article",["op" => "show", "result" => $result]);

    }
    public function editArticle($id){
        $results = DB::select('select * from food_info_article where id =?',[$id]);
        $special_categorys = DB::select('select * from food_info_article a left join food_info_article_category c on c.id = a.article_category where a.id =?',[$id]);
        $cates = DB::select('select * from food_info_article_category where id =?',[$id]);
        $result = "";
        if(!empty($results)){
            $result = $results[0];
        }
        if(Request::ajax()){
            $title = trim($_POST["title"]);
            if(empty($title)){
                return array(
                    "status" => 0,
                    "info" => "文章标题不能为空！"
                );
            }
            if(empty($id)){
                $data = [$title,$_POST["thumb"],$_POST["content"],$_POST["state"]];
                $result = DB::insert('insert into food_info_article (article_title, article_thumb, article_content, article_state) values (?, ?, ?, ?)', $data);
            }else{
                $data = [$title,$_POST["thumb"],$_POST["content"],$_POST["state"],$id];
                $result = DB::update('update food_info_article set article_title = ?, article_thumb = ?, article_content = ?, article_state = ? where id = ?', $data);
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

        return view("web/article",["op" => "edit", "result" => $result, "special_categorys" => $special_categorys, "cates" => $cates]);

    }
    public function delArticle($id){
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
