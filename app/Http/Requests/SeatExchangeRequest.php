<?php

namespace App\Http\Requests;

use App\Committee;
use App\Http\Requests\Request;

class SeatExchangeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ["target"=>"require|integer"];
        $committees = Committee::all("abbreviation","limit");
        foreach ($committees as $committee) {
            $rules[$committee->abbreviation."-in"] = "required|integer|min:0|max:".$committee->limit;
            $rules[$committee->abbreviation."-out"]="required|integer|min:0|max:".$committee->limit;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'min' => ':attribute 必须大于 :value',
            'max'=>':attribute 必须小于 :value'
        ];
    }
}
