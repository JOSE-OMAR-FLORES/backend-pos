<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Si quieres crear usuarios de prueba

        // Llama a tus seeders personalizados aquí
        $this->call([
            ProductSeeder::class,
            // Si creas más seeders (ej. UserSeeder, CategorySeeder), los añades aquí:
            // UserSeeder::class,
            // CategorySeeder::class,
        ]);
    }
}