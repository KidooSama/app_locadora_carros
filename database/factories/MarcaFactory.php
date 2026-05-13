<?php

namespace Database\Factories;

use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarcaFactory extends Factory
{
    protected $model = Marca::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->company(),
            'imagem' => $this->faker->imageUrl()
        ];
    }
}
