<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|unique:products,name',
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required',
            'image_url' => 'nullable|url',        
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do produto é obrigatório',
            'name.unique' => 'O produto já existe',
            'price.required' => 'O preço do produto é obrigatório',
            'price.numeric' => 'O preço do produto deve ser um número',
            'description.required' => 'A descrição do produto é obrigatória',
            'category.required' => 'A categoria do produto é obrigatória',
            'image_url.url' => 'A URL da imagem deve ser válida',
        ];
    }

    public function failedValidation($validator)
    {
        $response = response()->json([
            'errors' => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
