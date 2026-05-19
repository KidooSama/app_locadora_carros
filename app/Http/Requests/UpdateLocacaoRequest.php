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
}
