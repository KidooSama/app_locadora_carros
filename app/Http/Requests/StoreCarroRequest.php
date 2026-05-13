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
}
