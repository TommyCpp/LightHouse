<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommitteeTest extends TestCase
{
    private $committee_id = 100;
    /**
     *测试创建委员会表单
     */
    public function testCreateCommittee()
    {
        $this->actingAs(User::find(15));
        $this->visit("/create-committee")
            ->type($this->committee_id, "id")
            ->type("测试委员会", "chinese_name")
            ->type("Security Council", "english_name")
            ->select("1", "delegation")
            ->select("chinese","language")
            ->type("SC", "abbreviation")
            ->type("24", "number")
            ->type("无议题", "topic_chinese_name")
            ->type("No Topic", "topic_english_name")
            ->type("Testing Note","note")
            ->press("现在提交")
            ->seeInDatabase("committees", ['chinese_name' => '测试委员会']);

    }

    public function testReadNote(){
        $this->actingAs(User::find(15));
        $this->get('/committee/'.$this->committee_id.'/note',['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->see("Testing Note");
    }

    public function testUpdateCommittee()
    {
        $this->actingAs(User::find(15));
        $this->visit("/committee/".$this->committee_id."/edit")
            ->type("修改后的测试议题","topic_chinese_name")
            ->type("修改后的备注","note")
            ->press("现在提交")
            ->seePageIs("/committees")
            ->seeInDatabase("committees",['id'=>$this->committee_id,"topic_chinese_name"=>"修改后的测试议题","note"=>"修改后的备注"]);
    }
    public function testDeleteCommittee(){
        Session::start();
        $this->actingAs(User::find(15));
        $this->post("/committee/".$this->committee_id,["_method"=>'DELETE',"_token"=>csrf_token()])
            ->dontSeeInDatabase("committees",['id'=>$this->committee_id]);
    }
}
