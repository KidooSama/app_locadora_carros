<?php

namespace Database\Factories;

use App\Models\Carro;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $kmInicial = $this->faker->numberBetween(1000, 100000);

        return [

            'cliente_id' => Cliente::factory(),

            'carro_id' => Carro::factory([

                'disponivel' => true,
                'km' => $kmInicial

            ]),

            'data_inicio_periodo' => now(),

            'data_final_previsto_periodo' => now()->addDays(7),

            'data_final_realizado_periodo' => null,

            'valor_diaria' => $this->faker->randomFloat(2, 100, 1000),

            'km_inicial' => $kmInicial,

            'km_final' => null,
        ];
    }
}
