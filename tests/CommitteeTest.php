<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommitteeTest extends TestCase
{
    /**
     *测试创建委员会表单
     */
    public function testCreateCommittee()
    {
        $this->actingAs(User::find(15));
        $this->visit("/create-committee")
            ->type("4","id")
            ->type("测试委员会","chinese_name")
            ->type("Security Council","english_name")
            ->select("1","delegation")
            ->type("SC","abbreviation")
            ->type("24","number")
            ->type("无议题","topic_chinese_name")
            ->type("No Topic","topic_english_name")
            ->press("现在提交")
            ->seeInDatabase("committees",['chinese_name'=>'测试委员会']);
        
        //删除测试用例
        DB::delete("DELETE FROM committees WHERE chinese_name='测试委员会'");
    }
}
