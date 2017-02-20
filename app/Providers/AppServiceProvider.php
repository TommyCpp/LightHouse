<?php

namespace App\Providers;

use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Validator;

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
                    //icon：图标代码
                    //offspring:子菜单
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '我的资料', 'url' => 'user-archive'],
                    ['name' => '会场管理', 'url' => "javascript:void(0)", 'offspring' => [['name' => '会场列表', 'url' => 'committees'], ['name' => '创建会场', 'url' => 'create-committee']]],
                    ['name'=>'日志', 'url' => 'logs'],
                    ['name'=>'配置更改','url'=>'javascript:void(0)','offspring'=>[
                        ['name'=>'邮件通知 - 名额交换发起','url'=>'config/mail/seat-exchange-applied'],
                        ['name'=>'邮件通知 - 名额交换完成', 'url' => 'config/mail/seat-exchanged']
                    ]]
                ]);
                return;
            }
            if (Auth::user()->hasRole("OT")) {
                $view->with('menus', [
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '代表团管理', 'url' => 'javascript:void(0)', 'offspring' => [
                        ['name' => '代表团列表', 'url' => 'delegations'],
                        ['name' => '创建代表团', 'url' => 'create-delegation'],
                        ['name' => '会场限额管理', 'url' => 'committees/limit']
                    ]
                    ],
                    ['name' => '我的资料', 'url' => 'user-archive'],
                    ['name' => '会场管理', 'url' => "javascript:void(0)", 'offspring' => [
                        ['name' => '会场列表', 'url' => 'committees']
                    ]
                    ]
                ]);
                return;
            }
            if (Auth::user()->hasRole('HEADDEL')) {
                $view->with('menus', [
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '代表团管理', 'url' => 'javascript:void(0)', 'offspring' => [
                        ['name' => '代表团信息', 'url' => 'delegation/' . Auth::user()->delegation->id],
                        ['name' => '代表团名额交换', 'url' => 'delegation-seat-exchange'],
                    ]],
                    ['name' => '我的资料', 'url' => 'user-archive']
                ]);
                return;
            }

            $view->with('menus', [
                ['name' => "首页", 'url' => 'home']
            ]);
        });


        //Validation
        Validator::extend("even", function ($attribute, $value, $para, $validator) {
            return $value % 2 == 0;
        });
        Validator::extend("equal_to_total_seat", function ($attribute, $value, $para, $validator) {
            $total = 0;
            $data = $validator->getData();
            foreach ($para as $item)
                $total += array_get($data, $item);
            return $value == $total;
        });
        Validator::extend("emails", function ($attributes, $value, $para, $validator) {
            if (!is_array($value)) {
                //如果是数组
                $value = explode(",", $value);
                foreach($value as $item){
                    $validator = Validator::make(['email'=>$item],['email' => 'required|email']);
                    if($validator->fails()){
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
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
