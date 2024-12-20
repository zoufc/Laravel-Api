<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInvoiceRequest extends FormRequest
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
            //"user_id"=>"required|integer",
            "products"=>"required|array",
            "amount"=>"sometimes|integer",
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            //"user_id.required"=>"userId requis",
            "products.required"=>"productId requis"
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
