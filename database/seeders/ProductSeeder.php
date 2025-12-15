<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Shampoo Hidratante',
            'description' => 'Shampoo de hidratación profunda para cabello seco y dañado',
            'price' => 25.99,
            'image_url' => 'https://via.placeholder.com/300x250?text=Shampoo',
        ]);

        Product::create([
            'name' => 'Acondicionador Regenerador',
            'description' => 'Acondicionador regenerador con proteínas de seda',
            'price' => 22.50,
            'image_url' => 'https://via.placeholder.com/300x250?text=Acondicionador',
        ]);

        Product::create([
            'name' => 'Mascarilla Facial Purificante',
            'description' => 'Mascarilla facial purificante con carbón activo y arcilla',
            'price' => 18.99,
            'image_url' => 'https://via.placeholder.com/300x250?text=Mascarilla',
        ]);

        Product::create([
            'name' => 'Sérum Antienvejecimiento',
            'description' => 'Sérum concentrado con vitamina C y ácido hialurónico',
            'price' => 45.00,
            'image_url' => 'https://via.placeholder.com/300x250?text=Serum',
        ]);

        Product::create([
            'name' => 'Crema Hidratante Facial',
            'description' => 'Crema hidratante facial con extracto de rosa mosqueta',
            'price' => 35.99,
            'image_url' => 'https://via.placeholder.com/300x250?text=Crema',
        ]);

        Product::create([
            'name' => 'Exfoliante Corporal',
            'description' => 'Exfoliante corporal con sal marina y aceite de almendra',
            'price' => 15.50,
            'image_url' => 'https://via.placeholder.com/300x250?text=Exfoliante',
        ]);

        Product::create([
            'name' => 'Perfume Floral',
            'description' => 'Perfume floral con notas de jazmín y rosa',
            'price' => 55.00,
            'image_url' => 'https://via.placeholder.com/300x250?text=Perfume',
        ]);

        Product::create([
            'name' => 'Labial Matte',
            'description' => 'Labial de larga duración con acabado mate y tonos vibrantes',
            'price' => 12.99,
            'image_url' => 'https://via.placeholder.com/300x250?text=Labial',
        ]);
    }
}
