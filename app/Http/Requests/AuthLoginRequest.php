<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class AuthLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:8',

        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'email alanı zorunludur.',
            'email.string' => 'email alanı string karakterlerden olusur.',
            'email.email' => 'lütfen geçerli bir email giriniz',
            'email.max' => 'email alanı max 255 karakterden olusmak zorunda.',

            'password.required'=>'password alanı zorunludur.',
            'password.min'=>'password alanı en az 8 karakter olmalı.',
        ];
    }
    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([
            'statusCode' => 400,

            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ]));
    }
}
