<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:64',
            'type' => 'required|max:24',
            'price' => 'numeric|required|max:100000000|min:0',
            'quantity' => 'numeric|required|max:100|min:1',
            'images' => 'array|required',
            'description' => 'required|max:1500',
        ];
    }
}
