<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\User;

class LoginListener
{
    private $request;

    /**
     * Create the event listener.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $time = Carbon::now();
        $ip = $this->request->ip();
        Log::info("User Login", ['last_login' => $time, 'id' => $user->id, 'username' => $user->name, 'ip' => $ip, 'user-agent' => $this->request->header("User-Agent")]);
    }
}
