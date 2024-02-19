<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UpdateDutyRequest extends FormRequest
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
            'title' => 'string|max:255',
            'content' => 'string',
            'start' => 'date_format:Y-m-d H:i:s',
            'end' => 'date_format:Y-m-d H:i:s',
            'status' => 'required|in:none,start,working,completed'

        ];
    }
    public function messages()
    {
        return [
            'title.string' => 'title alanı string karakterlerden olusur.',
            'title.max' => 'title alanı max 255 karakterden olusmak zorunda.',

            'content.string' => 'content alanı string karakterlerden olusur.',

            'start.date_format' => 'start alanı datetime karakterlerden olusur.',

            'end.date_format' => 'end alanı datetime karakterlerden olusur.',

            'status.in'=>'status alanı dogru giriniz.',
            'status.required'=>'status alanı zorunludur.'
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
