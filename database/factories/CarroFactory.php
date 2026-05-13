<?php

namespace Database\Factories;

use App\Models\Carro;
use App\Models\Modelo;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarroFactory extends Factory
{
    protected $model = Carro::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'modelo_id' => Modelo::factory()->createOne(),
            'placa' => $this->faker->regexify('^[A-Z]{3}[0-9][A-Z][0-9]{2}$'),
            'disponivel' => $this->faker->boolean(),
            'km' => $this->faker->numberBetween(0,200000),

        ];
    }
}
