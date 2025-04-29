<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@heladeria.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Helados de Crema',
                'description' => 'Helados elaborados con base de crema y leche'
            ],
            [
                'name' => 'Helados de Agua',
                'description' => 'Helados elaborados con base de agua y frutas'
            ],
            [
                'name' => 'Toppings',
                'description' => 'Complementos para añadir a los helados'
            ],
            [
                'name' => 'Conos y Recipientes',
                'description' => 'Diferentes tipos de conos y recipientes para servir'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            [
                'name' => 'Helado de Vainilla',
                'description' => 'Delicioso helado de vainilla con extracto natural',
                'price' => 2.50,
                'stock' => 20,
                'min_stock' => 5,
                'image' => 'products/vanilla.jpg',
                'category_id' => 1,
            ],
            [
                'name' => 'Helado de Chocolate',
                'description' => 'Cremoso helado de chocolate belga',
                'price' => 2.75,
                'stock' => 18,
                'min_stock' => 5,
                'image' => 'products/chocolate.jpg',
                'category_id' => 1,
            ],
            [
                'name' => 'Sorbete de Fresa',
                'description' => 'Refrescante sorbete de fresas naturales',
                'price' => 2.25,
                'stock' => 15,
                'min_stock' => 4,
                'image' => 'products/strawberry.jpg',
                'category_id' => 2,
            ],
            [
                'name' => 'Sorbete de Limón',
                'description' => 'Sorbete de limón, ideal para días calurosos',
                'price' => 2.00,
                'stock' => 12,
                'min_stock' => 4,
                'image' => 'products/lemon.jpg',
                'category_id' => 2,
            ],
            [
                'name' => 'Chispas de Chocolate',
                'description' => 'Chispas de chocolate para decorar helados',
                'price' => 0.75,
                'stock' => 30,
                'min_stock' => 10,
                'image' => 'products/chocolate-chips.jpg',
                'category_id' => 3,
            ],
            [
                'name' => 'Cono Tradicional',
                'description' => 'Cono clásico para servir helados',
                'price' => 0.50,
                'stock' => 100,
                'min_stock' => 20,
                'image' => 'products/cone.jpg',
                'category_id' => 4,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
