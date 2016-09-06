<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = [
      'id','chinese_name','english_name','topic_english_name','topic_chinese_name','delegation','number'
    ];
    
    public function getTopicChineseNameAttribute($value){
        if($value == null || $value == ""){
            return "无议题";
        }
    }
    
    public function getTopicEnglishNameAttribute($value){
        if($value == null || $value == ""){
            return "No Topic";
        }
    }
}
