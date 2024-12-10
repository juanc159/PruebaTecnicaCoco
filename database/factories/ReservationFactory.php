<?php
namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ReservationFactory extends Factory
{
    // Especificamos el modelo que esta fábrica va a usar
    protected $model = Reservation::class;

    /**
     * Define el estado inicial del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resource_id' => Resource::factory(), // Se genera un recurso relacionado
            'reserved_at' => $this->faker->dateTimeBetween('+1 days', '+1 week'), // Fecha de reserva entre 1 y 7 días a partir de hoy
            'duration' => $this->faker->numberBetween(30, 240), // Duración entre 30 y 240 minutos
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']), // Estado de la reserva
        ];
    }
}
