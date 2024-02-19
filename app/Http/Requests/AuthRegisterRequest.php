<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
class AuthRegisterRequest extends FormRequest
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

            'name' => 'required|string|max:255|regex:/^[A-Za-z_]+$/',
            'email' => 'required|unique:users|string|email|max:255',
            'password' => 'required|min:8|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'required|min:8',
            'status' => 'required|in:admin,normal'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name alanı zorunludur.',
            'name.string' => 'name alanı string karakterlerden olusur.',
            'name.max' => 'name alanı max 255 karakter içerir.',
            'name.regex' => 'regex karakter kullanmayınız.',

            'email.required' => 'email alanı zorunludur.',
            'email.unique' => 'ilgili email kullanılmakta.',
            'email.string' => 'email alanı string karakterlerden olusur.',
            'email.email' => 'lütfen geçerli bir email giriniz',
            'email.max' => 'email alanı max 255 karakterden olusmak zorunda.',

            'password.required'=>'password alanı zorunludur.',
            'password.min'=>'password alanı en az 8 karakter olmalı.',
            'password.required_with'=>'password_confirm alanı zorunludur.',
            'password.same'=>'password_confirm ile password aynı olmalıdır.',

            'password_confirm.required'=>'password_confirm alanı zorunludur.',
            'password_confirm.min'=>'password_confirm alanı en az 8 karakter olmalı.',

            'status.required'=>'status alanı zorunludur.',
            'status.in'=>'status alanı dogru giriniz.'
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
