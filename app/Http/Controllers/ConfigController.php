<?php

namespace App\Http\Controllers;

use Config;
use File;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ConfigController
 * @package App\Http\Controllers
 * Handle the change to config
 */
class ConfigController extends Controller
{
    public function __construct()
    {
    }

    /**
     * GET config/{config_file}/{config}
     * @param Request $request
     * @param $config_file
     * @param $config
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, $config_file, $config)
    {
        if ($config_file == "mail") {
            //席位交换申请被发起的时候的配置
            if ($config == "seat-exchange-applied") {
                return view("config.mail.seat-exchange-applied&seat-exchanged", ['configs' => config("maillist.notify.seat_exchange_applied")]);
            };
            if ($config == "seat-exchanged") {
                return view("config.mail.seat-exchange-applied&seat-exchanged", ['configs' => config("maillist.notify.seat_exchanged")]);
            }
        }

        return response("", 404);
    }


    /**
     * POST config/{config_file}/{config}
     * @param Request $request
     * @param $config_file
     * @param $config
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(Request $request, $config_file, $config)
    {
        if ($config_file == "mail") {
            $rules = [
                "sender" => "required|email",
                "ccs" => "required|emails",
                "initiator_subject" => "required",
                "target_subject" => "required",
                "emergence_contact" => "required|email"
            ];
            $messages = [
                "required" => ":attribute 为必填项",
                "email" => ":attribute 必须为电子邮件",
                "emails" => ":attribute 必须为电子邮件"
            ];
            $attribute = [
                "sender" => "发件人",
                "ccs" => "抄送地址",
                "initiator_subject" => "名额交换发起者 - 标题",
                "target_subject" => "名额交换目标 - 标题",
                "emergence_contact" => "紧急联系地址"
            ];
            if ($config == "seat-exchange-applied") {
                //席位交换申请被发起的时候的配置
                $this->validate($request, $rules, $messages, $attribute);
                $config_array = $this->prepossess($request->input(), "mail");
                Config::set("maillist.notify.seat_exchange_applied", $config_array);
                $config_data = var_export(Config::get("maillist"), 1);
                //using var_export to print the literal of config array

                if (File::put(env("ROOT_PATH") . "/config/maillist.php", "<?php\n return $config_data ;")) {
                    return redirect($request->url());
                }//using File facade to modify config file,if success then redirect
            }
            if ($config == "seat-exchanged") {
                //席位交换完成的配置
                $this->validate($request, $rules, $messages, $attribute);
                $config_array = $this->prepossess($request->input(), "mail");
                Config::set("maillist.notify.seat_exchanged", $config_array);
                $config_data = var_export(Config::get("maillist"), 1);
                if (File::put(env("ROOT_PATH") . "/config/maillist.php", "<?php\n return $config_data ;")) {
                    return redirect($request->url());
                }
            }
        }
    }

    /**
     * prepossess the request variable
     * @param $input
     * @param string $type
     * @return bool|Request
     * @internal param Request $request
     */
    private function prepossess($input, $type)
    {
        if ($type == "mail") {
            $input['ccs'] = explode(",", $input['ccs']);//convert the string to array by explode
            array_forget($input, "_token");//remove _token
            return $input;//now has every key config needs
        }
    }
}
