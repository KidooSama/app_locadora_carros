<?php

namespace Database\Factories;

use App\Models\Marca;
use App\Models\Modelo;

use Illuminate\Database\Eloquent\Factories\Factory;
class ModeloFactory extends Factory
{
     protected $model = Modelo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'marca_id' => Marca::factory()->createOne(),
            'nome' => $this->faker->word(),
            'imagem' => $this->faker->imageUrl(),
            'numero_portas' => $this->faker->numberBetween(2,10),
            'lugares' => $this->faker->numberBetween(2,7),
            'air_bag' => $this->faker->boolean(),
            'abs' => $this->faker->boolean(),
        ];
    }
}
