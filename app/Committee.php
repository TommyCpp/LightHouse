<?php

namespace App;

use Auth;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Log;

class Committee extends Model
{
    protected $fillable = [
        'id', 'chinese_name', 'english_name', 'abbreviation', 'topic_english_name', 'topic_chinese_name', 'language', 'delegation', 'number', 'note'
    ];

    public $incrementing = false;

    protected $casts = [
        "id" => 'integer'
    ];

    public function seats()
    {
        return $this->hasMany("App\\Seat", "committee_id", "id");
    }


    public function getTopicChineseNameAttribute($value)
    {
        if ($value == null || $value == "") {
            return "无议题";
        }
        return $value;
    }

    public function getTopicEnglishNameAttribute($value)
    {
        if ($value == null || $value == "") {
            return "No Topic";
        }
        return $value;
    }

    public function getAbbreviationAttribute($value)
    {
        return strtoupper($value);
    }

    public function getFormatLanguageAttribute()
    {
        if ($this->language == "chinese") {
            return "中文";
        } else {
            return "English";
        }
    }

    /*
     * 以下是静态方法和Event Handler
     * 用于描述所有委员会Model的性质
     */
    /*
     * ================================================================
     */
    /**
     * 按照[委员会id=>委员会Model]实体的形式组织一个Collection并返回
     * @return Collection
     */
    public static function allInOrder()
    {
        $committees = Committee::all();
        $result = new Collection();
        foreach ($committees as $committee) {
            $result[$committee->id] = $committee;
        }
        return $result;
    }

    public static function allInCache($column = ["*"])
    {
        return Cache::remember("committees", 24 * 60, function (){
            return Committee::allInOrder();
        });
    }

    //Event Handler
    protected static function boot()
    {
        parent::boot();//先执行parent的方法

        static::created(function ($committee) {
            //Update Cache
            if (Cache::has("committees")) {
                $committees = Cache::get("committees");
                $committees[$committee->id] = $committee;
                Cache::put("committees", $committees, 24 * 60);
            } else {
                Cache::put("committees", Committee::allInOrder(), 24 * 60);
            }
            Log::info("New committee has been created", [
                "operator" => Auth::user()->name,
                "committee_id" => $committee->id
            ]);
        });

        static::updated(function ($committee) {
            if (Cache::has("committees")) {
                $original_committees = Cache::get("committees");
                $committees = $original_committees->reject(function ($c) use ($committee) {
                    return $c->id == $committee->id;
                });
                /** @var Collection $committees */
                $committees[$committee->id] = $committee;
                Cache::put("committees", $committees, 24 * 60);
            } else {
                Cache::put("committees", Committee::allInOrder());
            }
        });

        static::deleted(function ($committee) {
            if (Cache::has("committees")) {
                $committees = Cache::get("committees");
                $committees->forget($committee->id);
                Cache::put("committees", $committees, 24 * 60);
            } else {
                Cache::put("committees", Committee::allInOrder());
            }
        });
    }

}
