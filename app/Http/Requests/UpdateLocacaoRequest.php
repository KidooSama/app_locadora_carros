<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocacaoRequest extends FormRequest
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
            'cliente_id' => 'sometimes|required|exists:clientes,id',

            'carro_id' => 'sometimes|required|exists:carros,id',

            'data_inicio_periodo' => 'sometimes|required|date|after_or_equal:today',

            'data_final_previsto_periodo' =>
                'sometimes|required|date|after:data_inicio_periodo',

            'data_final_realizado_periodo' =>
                'sometimes|nullable|date|after_or_equal:data_inicio_periodo',

            'valor_diaria' =>
                'sometimes|required|numeric|min:0',

            'km_final' =>
                'sometimes|nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'Selecione um cliente.',
            'cliente_id.exists' => 'O cliente informado não existe.',
            'carro_id.required' => 'Selecione um carro.',
            'carro_id.exists' => 'O carro informado não existe.',
            'data_inicio_periodo.required' => 'Informe a data de início.',
            'data_inicio_periodo.date' => 'Data de início inválida.',
            'data_final_previsto_periodo.required' => 'Informe a data de previsão.',
            'data_final_previsto_periodo.date' => 'Data de previsão inválida.',
            'data_final_previsto_periodo.after' => 'A data de previsão deve ser posterior à data de início.',
            'data_final_realizado_periodo.date' => 'Data de finalização inválida.',
            'data_final_realizado_periodo.after_or_equal' => 'A data de finalização não pode ser anterior à data de início.',
            'valor_diaria.required' => 'Informe o valor da diária.',
            'valor_diaria.numeric' => 'Valor da diária deve ser numérico.',
            'valor_diaria.min' => 'Valor da diária não pode ser negativo.',
            'km_final.integer' => 'Km final deve ser um número inteiro.',
        ];
    }
}
