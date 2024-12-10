<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    // Define el modelo que esta fábrica usará.
    protected $model = Resource::class;

    /**
     * Define el estado inicial del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'capacity' => $this->faker->numberBetween(1, 10),

        ];
    }
}
