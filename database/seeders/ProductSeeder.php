<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Asegúrate de importar tu modelo Product

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
    'name' => 'Cheeseburger',
    'price' => 5.99,
    'available' => true,
    'category' => 'hamburguesas',
]);


        Product::create([
            'name' => 'Papas Fritas Grandes',
            'description' => 'Crujientes papas fritas, tamaño grande.',
            'price' => 3.00,
            'category' =>  'hamburguesas'
        ]);

        Product::create([
            'name' => 'Refresco Cola',
            'description' => 'Bebida carbonatada de cola.',
            'price' => 2.25,
            'category' => 'bebidas'
        ]);

        Product::create([
            'name' => 'Nuggets de Pollo (6 und)',
            'description' => '6 piezas de nuggets de pollo con salsa.',
            'price' => 5.75,
            'category' => 'hamburguesas'
        ]);

        // Añade más productos si lo deseas
    }
}