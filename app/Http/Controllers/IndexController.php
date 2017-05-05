<?php

namespace App\Http\Controllers;

use Aimeos\Shop\Base\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \DB;
use \Request;

class IndexController extends Controller
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

    public function showIndex(){
        $navs = DB::select('select * from food_info_adv where enabled = :enabled', ['enabled' => 1]);
        $articles = DB::select('select a.*, c.category_name from food_info_article a left join food_info_article_category c on c.id = a.article_category where a.article_state = :article_state', ['article_state' => 1]);
        return view('welcome',['navs' => $navs,'articles' => $articles]);

    }

}
