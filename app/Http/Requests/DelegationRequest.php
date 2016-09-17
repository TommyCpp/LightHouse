<?php

namespace App\Http\Requests;

use App\Committee;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class DelegationRequest extends Request
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
        //验证
        $committees_double = Committee::all()->where('delegation', 2);//所有双代委员会
        $committees = Committee::all()->all();//所有委员会名称集合
        $names = [];
        for ($i = 0; $i < count($committees); $i++) {
            $names[$i] = $committees[$i]->abbreviation;
        }
        $names = implode(",", $names);//委员会名称数组

        $committees_validation = [];
        foreach ($committees_double as $committee) {
            $committees_validation[$committee->abbreviation] = "required|even";
        }//为所有双代委员会

        $rules = [
            'name' => 'required',
            'delegate_number' => 'required|equal_to_total_seat:' . $names . '|min:1'
        ];
        $rules = array_merge($rules, $committees_validation);
        return $rules;
    }

    public function messages(){
        return [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values',
            'even' => ':attribute 必须是一个偶数',
            'equal_to_total_seat' => '代表团人数与席位总数不符合'
        ];
    }
}
