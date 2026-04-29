<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarcaRequest extends FormRequest
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
            'nome' => 'sometimes|required|unique:marcas,nome,'.$this->route('marca'),
            'imagem' => 'sometimes|required|image|mimes:png'
        ];
    }

        public function messages()
    {
        return [
            'nome.unique' => 'Esse nome já está sendo usado.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser do tipo PNG.'
        ];
    }
    public function attributes()
    {
        return [
            'nome' => 'Nome da marca',
            'imagem' => 'Imagem da marca'
        ];
    }
}
