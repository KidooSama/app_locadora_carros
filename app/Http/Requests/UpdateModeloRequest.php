<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModeloRequest extends FormRequest
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
        'marca_id' => 'sometimes|exists:marcas,id',
        'nome' => 'sometimes|required|min:3|unique:modelos,nome,'.$this->route('modelo'),
        'imagem' => 'sometimes|image|mimes:png',
        'numero_portas' => 'sometimes|integer|digits_between:1,5',
        'lugares' => 'sometimes|integer|digits_between:1,20',
        'air_bag' => 'sometimes|boolean',
        'abs' => 'sometimes|boolean'
    ];
}
}
