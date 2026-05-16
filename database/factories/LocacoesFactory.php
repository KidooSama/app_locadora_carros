<?php

namespace Database\Factories;

use App\Models\Carro;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocacoesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cliente_id' => Cliente::factory()->createOne(),
            'carro_id' => Carro::factory()->createOne(),
            'data_inicio_periodo' => $this->faker->date(),
            'data_final_previsto_periodo' => $faker->dateTimeThisMonth('+12 days'),
            'data_final_realizado_periodo' => $this->faker->date(),
            'valor_diaria' => ,
            'km_inicial' => ,
            'km_final' => ,
        ];
    }
}
