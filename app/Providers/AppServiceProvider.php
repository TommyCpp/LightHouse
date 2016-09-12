<?php

namespace App\Providers;

use Auth;
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
        view()->composer('partial/menu', function (View $view) {
            if (Auth::user()->hasRole('ADMIN')) {
                $view->with('menus', [
                    //name:选项名称
                    //url：相对URL
                    //link：绝对URL
                    //icon：图标代码
                    //offspring:子菜单
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '我的资料', 'url' => 'user-archive'],
                    ['name' => '会场管理', 'link' => "javascript:void(0)", 'offspring' => [['name' => '会场列表', 'url' => 'committees'], ['name' => '创建会场', 'url' => 'create-committee']]]
                ]);
                return;
            }
            if (Auth::user()->hasRole("OT")) {
                $view->with('menus', [
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '代表团管理', 'link' => 'javascript:void(0)', 'offspring' => [
                        ['name'=>'代表团列表','url'=>'delegations'],
                        ['name'=>'创建代表团','url'=>'create-delegation']
                    ]
                    ],
                    ['name' => '我的资料', 'url' => 'user-archive'],
                    ['name' => '会场管理', 'link' => "javascript:void(0)", 'offspring' => [
                        ['name' => '会场列表', 'url' => 'committees']
                    ]
                    ]
                ]);
                return;
            }
            if (Auth::user()->hasRole('HEADDEL')) {
                $view->with('menus', [
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '我的资料', 'url' => 'user-archive']
                ]);
            }

            $view->with('menus', [
                ['name' => "首页", 'url' => 'home']
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
