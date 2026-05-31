<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng dengan bumbu khas Indonesia',
                'price' => 15000,
                'stock' => 100,
                'is_active' => true,
                'category_id' => 1, // Makanan
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Es teh manis segar',
                'price' => 5000,
                'stock' => 200,
                'is_active' => false,
                'category_id' => 2, // Minuman
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
