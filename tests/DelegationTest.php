<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DelegationTest extends TestCase
{
    use DatabaseTransactions;
    public function testCreate()
    {
        $this->actingAs(User::find(18));
        $this->visit("/create-delegation")
            ->type("第五测试代表团","name")
            ->type("1","delegate_number")
            ->select("22","head_delegate_id")
            ->type("1","ASS")//应该为双数
            ->press("现在提交")
            ->seePageIs("/create-delegation");
    }
    
    public function testChangeCommitteeLimit(){
        $this->actingAs(User::find(18));
        $this->visit("/committees/limit")
            ->type("2","ASS")
            ->press("现在提交")
            ->seeInDatabase("committees",['abbreviation'=>'ASS','limit'=>'2']);
    }
}
