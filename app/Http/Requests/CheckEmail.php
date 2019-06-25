<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckEmail extends FormRequest
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
        return [
            'u_email' => 'required',
        ];
    }
    /**
     * 验证的提示信息
     */
    public function messages()
    {
        return [
            'u_email.required' => '手机号或邮箱不能为空',
        ];
    }
}
