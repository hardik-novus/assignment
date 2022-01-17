<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:10',
            'address' => 'nullable|max:65535',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'is_vaccinated' => 'nullable|in:YES,NO,yes,no',
            'vaccine_name' => 'required_if:is_vaccinated,YES,yes|in:COVAXIN,covaxin,COVISHIELD,covishield',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->errors()->first(), 400));
    }
}
