<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = [
      'id','chinese_name','english_name','abbreviation','topic_english_name','topic_chinese_name','delegation','number'
    ];
    
    public function getTopicChineseNameAttribute($value){
        if($value == null || $value == ""){
            return "无议题";
        }
        return $value;
    }
    
    public function getTopicEnglishNameAttribute($value){
        if($value == null || $value == ""){
            return "No Topic";
        }
        return $value;
    }
    
    public function getAbbreviationAttribute($value){
        return strtoupper($value);
    }
    
}
