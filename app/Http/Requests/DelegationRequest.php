<?php

namespace App\Http\Requests;

use App\Committee;
use App\Http\Requests\Request;
use Cache;
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
        $committees = Cache::remember("committees",24*60, function () {
            return Committee::all();
        });//所有委员会信息集合
        $committees = $committees->all();//获得原生数组而不是Collection
        $names = [];
        for ($i = 0; $i < count($committees); $i++) {
            $names[$i] = $committees[$i]->abbreviation;
        }
        $names = implode(",", $names);//委员会名称数组

        $committees_validation = [];
        foreach ($committees as $committee) {
            if ($committee->delegation == 2)
                $committees_validation[$committee->abbreviation] = "required|even";
            else
                $committees_validation[$committee->abbreviation] = "required";
        }

        $rules = [
            'name' => 'required',
            'delegate_number' => 'required|equal_to_total_seat:' . $names . '|min:1'
        ];
        $rules = array_merge($rules, $committees_validation);
        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values',
            'even' => ':attribute 代表数必须是一个偶数',
            'equal_to_total_seat' => '代表团人数与席位总数不符合'
        ];
    }
}
