<?php
// Script para verificar productos en la BD

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$config = [
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
];

$capsule = new DB();
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$products = DB::table('products')->select('id', 'name', 'image_url')->limit(10)->get();

echo "=== PRODUCTOS EN BASE DE DATOS ===\n";
foreach ($products as $product) {
    echo "\nID: {$product->id}\n";
    echo "Nombre: {$product->name}\n";
    echo "Imagen URL: " . ($product->image_url ? $product->image_url : 'NULL') . "\n";
    echo "---\n";
}

echo "\n=== TOTAL: " . count($products) . " productos ===\n";
