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
            'cliente_id' => 'sometimes|exists:clientes,id',
            'carro_id' => 'sometimes|exists:carros,id',
            'data_inicio_periodo' => 'sometimes|required',
            'data_final_previsto_periodo' => 'sometimes|required',
            'data_final_realizado_periodo' => 'sometimes|required',
            'valor_diaria' => 'sometimes|required',
            'km_inicial' => 'sometimes|required',
            'km_final' => 'sometimes|required',
        ];
    }
}
