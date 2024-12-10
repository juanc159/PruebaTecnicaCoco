<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crea 10 recursos de manera rápida
        Resource::factory()->count(10)->create();
    }
}
