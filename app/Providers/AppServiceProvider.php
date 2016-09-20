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

            /*
             * $menus = [
                ['name' => '首页', 'url' => 'home'],
                ['name' => '我的资料', 'url' => 'users']
            ];

            if (Auth::user()->hasRole('ADMIN')) {
                $key = array_search("会场管理", array_pluck($menus, 'name'));
                if ($key !== false) {
                    if (array_search("会场列表", array_pluck($menus[$key]['offspring'], 'name')) === false) {
                        $menus[$key]['offspring'][] = ['name' => '会场列表', 'url' => 'committees'];
                    }
                    if (array_search("创建会场", array_pluck($menus[$key]['offspring'], 'name')) === false) {
                        $menus[$key]['offspring'][] = ['name' => '创建会场', 'url' => 'create-committee'];
                    }
                }
                else{
                    $menus[] = ['name' => '会场管理', 'url' => "javascript:void(0)", 'offspring' => [['name' => '会场列表', 'url' => 'committees'], ['name' => '创建会场', 'url' => 'create-committee']]];
                }

                $key = array_search("代表团管理", array_pluck($menus, 'name'));
            }
            */

            if (Auth::user()->hasRole('ADMIN')) {
                $view->with('menus', [
                    //name:选项名称
                    //url：相对URL
                    //icon：图标代码
                    //offspring:子菜单
                    ['name' => '首页', 'url' => 'home'],
                    ['name' => '用户管理', 'url' => "users"],
                    ['name' => '我的资料', 'url' => 'user-archive'],
                    ['name' => '会场管理', 'url' => "javascript:void(0)", 'offspring' => [['name' => '会场列表', 'url' => 'committees'], ['name' => '创建会场', 'url' => 'create-committee']]]
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
            for ($i = 0; $i < count($para); $i++)
                $total += array_get($data, $para[$i]);
            return $value == $total;
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


//    /**
//     * @param $array
//     * @param $key
//     * @param $value
//     * @return bool|int
//     * 针对二维数组（第一维是普通数组，第二维是关联数组），查询$key,$value是否在该数组中，如果在返回对应数组在$array中的索引，否则返回false
//     */
//    private function double_array_find($array, $key, $value)
//    {
//        for ($i = 0; $i < count($array); $i++) {
//            if ($array[$i][$key] == $value) {
//                return $i;
//            }
//        }
//        return false;
//    }
}
