<?php

namespace Tests\Feature;

use App\Constants\MessagesAlert;
use Tests\TestCase;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de crear reserva con éxito.
     *
     * @return void
     */
    public function test_create_reservation_success()
    {
        // Crear un usuario y obtener el token JWT
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);
        $token = auth()->login($user); // Obtener token JWT

        // Crear un recurso
        $resource = Resource::factory()->create();

        // Crear una reserva
        $response = $this->postJson('/api/reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => now()->addHours(2),
            'duration' => 60,
        ], [
            'Authorization' => 'Bearer ' . $token // Incluir el token JWT
        ]);

        $response->assertStatus(201); // Esperamos que se haya creado con éxito
        $response->assertJsonStructure([
            'reservation' => [ 'resource_id', 'reserved_at', 'duration',"status","id","updated_at","created_at"],

        ]);
    }

    /**
     * Test de creación de reserva con conflicto de horario.
     *
     * @return void
     */
    public function test_create_reservation_conflict()
    {
        // Crear un usuario y obtener el token JWT
        $user = User::factory()->create([
            'email' => 'user111@example.com',
            'password' => Hash::make('password123'),
        ]);
        $token = auth()->login($user); // Obtener token JWT

        // Crear un recurso
        $resource = Resource::factory()->create();

        // Crear una primera reserva en un horario específico
        Reservation::factory()->create([
            'resource_id' => $resource->id,
            'reserved_at' => now()->addHours(2),
            'duration' => 60,
        ]);

        // Intentar crear una reserva en el mismo horario (conflicto)
        $response = $this->postJson('/api/reservations', [
            'resource_id' => $resource->id,
            'reserved_at' => now()->addHours(2),
            'duration' => 60,
        ], [
            'Authorization' => 'Bearer ' . $token // Incluir el token JWT
        ]);

        $response->assertStatus(400); // Esperamos que falle con código 400
        $response->assertJson(['error' => MessagesAlert::ERROR_MESSAGE_001]);
    }

    /**
     * Test de cancelar reserva.
     *
     * @return void
     */
    public function test_cancel_reservation()
    {
        // Crear un usuario y obtener el token JWT
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);
        $token = auth()->login($user); // Obtener token JWT

        // Crear una reserva
        $reservation = Reservation::factory()->create();

        // Cancelar la reserva
        $response = $this->deleteJson("/api/reservations/{$reservation->id}", [], [
            'Authorization' => 'Bearer ' . $token // Incluir el token JWT
        ]);

        $response->assertStatus(200); // Esperamos que la cancelación sea exitosa
        $response->assertJson(['message' => MessagesAlert::CACELLED_MESSAGE_001]);
    }
}
