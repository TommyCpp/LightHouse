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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ["target" => "required|integer"];
        $committees = Committee::all("abbreviation", "limit","delegation");
        foreach ($committees as $committee) {
            $rules[$committee->abbreviation . "-in"] = "required|integer|min:0|max:" . $committee->limit;
            $rules[$committee->abbreviation . "-out"] = "required|integer|min:0|max:" . $committee->limit;
            if ($committee->delegation == 2) {
                $rules[$committee->abbreviation . "-in"] .= "|even";
                $rules[$committee->abbreviation . "-out"] .= "|even";
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'min' => ':attribute 必须大于 :value',
            'max' => ':attribute 必须小于 :value',
            'even' => ':attribute 必须是一个偶数'
        ];
    }
}
