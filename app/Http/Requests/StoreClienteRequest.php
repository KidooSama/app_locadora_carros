<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
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
           'nome' => 'required|min:3|max:255|string'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'Informe o nome do cliente.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'nome.string' => 'O nome deve ser um texto válido.',
        ];
    }
}
