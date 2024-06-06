<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Controllers\Api\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator as ExtendValid;

class UpdateRequest extends FormRequest
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
        ExtendValid::extend('hasStore', function ($attribute, $value, $parameters) {
            if (auth()->user()->stores()->get()->contains('id', $value)) {
                return true;
            }

            return false;
        }, __('The store_id field is error.'));

        return [
            'name' => 'required|string',
            'slug' => 'required|string|unique:products',
            'price' => 'required|numeric',
            'store_id' => 'required|hasStore',
            'description' => 'string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::error(__('Validation failed!'), $validator->errors(), 400));
    }
}
