<?php

use App\Committee;
use App\Delegate;
use App\User;
use App\UserArchive;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testUser()
    {
        $identities = "DEL";
        $user = factory(User::class)->create();
        $user->archive->Identity = $identities;
        $user->save();

        $this->assertTrue($user->hasRole("DEL"));
        $this->assertEquals($user->id, $user->archive->user->id);//测试双向relation是否可以从两个Model中分别
        $this->assertFalse($user->hasRole("DAIS"));
        $this->assertNull($user->delegation);

        $this->assertEquals($this->translate($identities), $user->identities);

    }

    private function translate($identities)
    {
        $identities = explode(",", $identities);
        foreach ($identities as &$identity) {
            switch ($identity) {
                case "ADMIN":
                    $identity = "管理员";
                    break;
                case "DAIS":
                    $identity = "主席";
                    break;
                case "OT":
                    $identity = "会务运营团队";
                    break;
                case "AT":
                    $identity = "学术管理团队";
                    break;
                case "DIR":
                    $identity = "理事";
                    break;
                case "COREDIR":
                    $identity = "核心理事";
                    break;
                case "VOL":
                    $identity = "志愿者";
                    break;
                case "DEL":
                    $identity = "代表";
                    break;
                case "HEADDEL":
                    $identity = "代表团领队";
                    break;
                case "OTHER":
                    $identity = "其他";
                    break;
            }
        }
        return $identities;
    }

    public function testCommittee()
    {
        $user = factory(User::class)->create();
        $user->archive->identity = "OT,AT";
        $user->save();
        $this->actingAs($user);

        $committee = factory(Committee::class)->create([
            "topic_chinese_name" => null,
            "topic_english_name" => null,
            "language" => 'chinese'
        ]);
        $this->assertEquals("无议题", $committee->topic_chinese_name);
        $this->assertEquals("No Topic", $committee->topic_english_name);
        $this->assertEquals("中文", $committee->format_language);
    }
}
