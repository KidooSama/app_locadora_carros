<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarroRequest extends FormRequest
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
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => 'required|regex:/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/|size:7|unique:carros,placa',
            'disponivel' => 'required|boolean',
            'km' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'modelo_id.required' => 'Selecione um modelo.',
            'modelo_id.exists' => 'O modelo informado não existe.',
            'placa.required' => 'Informe a placa do carro.',
            'placa.regex' => 'A placa deve seguir o padrão Mercosul.',
            'placa.size' => 'A placa deve ter 7 caracteres.',
            'placa.unique' => 'Já existe um carro com essa placa.',
            'disponivel.required' => 'Informe se o carro está disponível.',
            'disponivel.boolean' => 'Disponibilidade deve ser verdadeiro/falso.',
            'km.required' => 'Informe a quilometragem.',
            'km.integer' => 'Quilometragem deve ser um número inteiro.',
            'km.min' => 'Quilometragem não pode ser negativa.',
        ];
    }
}
