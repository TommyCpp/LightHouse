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
                ['首页','home'],
                ['用户管理','user-management'],
                ['我的资料','user-archive'],
                ['会场管理','committees']
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
