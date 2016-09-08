<?php

namespace App\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts/menu',function(View $view){
            $view->with('menus',[
                //name:选项名称
                //url：相对URL
                //link：绝对URL
                //icon：图标代码
                //offspring:子菜单
                ['name'=>'首页','url'=>'home'],
                ['name'=>'用户管理','link'=>"javascript:void(0)",'offspring'=>[['name'=>'用户列表','url'=>'user-management'],['name'=>'创建用户','url'=>'new-user']]],
                ['name'=>'我的资料','url'=>'user-archive'],
                ['name'=>'会场管理','link'=>"javascript:void(0)",'offspring'=>[['name'=>'会场列表','url'=>'committees'],['name'=>'创建会场','url'=>'create-committee']]]
            ]);
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
