<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
//    use DatabaseTransactions;
    

    public function testCreateUser()
    {
        Session::start();
        $this->visit("/register")
            ->type("测试用户4","name")
            ->type("test4@test.com","email")
            ->type("123456","password")
            ->type("123456","password_confirmation")
            ->press("Register")
            ->seeInDatabase("users",['name'=>"测试用户4"]);
    }
    
}
