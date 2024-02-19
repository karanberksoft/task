<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class AddDutyRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s',
            'dutyUserEmail'=>'required|email'

        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'title alanı zorunludur.',
            'title.string' => 'title alanı string karakterlerden olusur.',
            'title.max' => 'title alanı max 255 karakterden olusmak zorunda.',

            'content.required' => 'content alanı zorunludur.',
            'content.string' => 'content alanı string karakterlerden olusur.',

            'dutyUserEmail.required' => 'dutyUserEmail alanı zorunludur.',
            'dutyUserEmail.email' => 'dutyUserEmail alanı email olmak zorundadır.',

            'start.required' => 'start alanı zorunludur.',
            'start.date_format' => 'start alanı datetime karakterlerden olusur.',

            'end.required' => 'end alanı zorunludur.',
            'end.date_format' => 'end alanı datetime karakterlerden olusur.',
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
