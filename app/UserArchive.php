<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArchive extends Model
{
    protected $fillable = [
        'id',
        'FirstName',
        'LastName',
        'HighSchool',
        'University',
        'Identity'
    ];

    public static function identities($id)
    {
        $user_archive = UserArchive::all()->find($id);
        $identity_string = $user_archive->Identity;
        if ($identity_string != null && $identity_string != "") {
            $identities = explode(",", $identity_string);
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
        return false;
    }

    public function user()
    {
        return $this->belongsTo('App\User','id','id');

    }
}
