<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            "firstname"=>"required|string",
            "lastname"=>"required|string",
            "phoneNumber"=>"required|unique:users",
            "email"=>"sometimes|email|unique:users",
            "password"=>"required"
        ];
    }

    public function messages()
    {
        return [
            "firstname.required"=>"Prénom requis",
            "lastname.required"=>"Nom requis",
            "phoneNumber.required"=>"Numéro de téléphone requis",
            "password.required"=>"Mot de passe requis"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
                throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400)
        );
    }
}
