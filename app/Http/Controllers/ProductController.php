<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\FirebaseServices; // ¡Importación necesaria!

class ProductController extends Controller
{
    protected FirebaseServices $firebaseService; // Tipado para la propiedad

    // 1. CONSTRUCTOR CORREGIDO: Inyección de dependencias
    public function __construct(FirebaseServices $firebaseService)
    {
        $this->firebaseService = $firebaseService; 
    }

    // LISTAR TODOS LOS PRODUCTOS
    public function index()
    {
        return Product::all();
    }

    // CREAR PRODUCTO
    public function store(Request $request)
    {
        // 2. VALIDACIÓN CORREGIDA: 'image' debe ser file/image
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string', // Añadido tipado si es API
            'price' => 'required|numeric',
            'image' => 'required|file|image|max:2048' // El archivo debe ser obligatorio
        ]);

        $data = $request->except('image'); // Tomamos todos los datos menos el archivo

        // LÓGICA DE SUBIDA DE IMAGEN
        if ($request->hasFile('image')) {
            // Llama al servicio de Firebase. La carpeta 'productos' está bien.
            $url = $this->firebaseService->uploadImage($request->file('image'), 'productos');
            $data['image_url'] = $url; // Guarda la URL en los datos
        } else {
            // Si la imagen fuera opcional, aquí se asignaría null
            $data['image_url'] = null;
        }

        // CREACIÓN DEL PRODUCTO con la URL de Firebase
        $product = Product::create($data);

        return response()->json([
            'message' => 'Producto creado con éxito',
            'product' => $product
        ], 201);
    }


    // MOSTRAR PRODUCTO POR ID
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return $product;
    }

    // ACTUALIZAR PRODUCTO
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        
        // Validación para actualización (los campos pueden ser opcionales)
        $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|file|image|max:2048' 
        ]);

        $data = $request->except('image'); // No actualizamos el campo 'image'

        // LÓGICA DE ACTUALIZACIÓN DE IMAGEN
        if ($request->hasFile('image')) {
            // Eliminar imagen antigua de Firebase si existe
            if ($product->image_url) {
                $this->firebaseService->deleteImage($product->image_url);
            }
            
            // Subir la nueva imagen
            $url = $this->firebaseService->uploadImage($request->file('image'), 'productos');
            $data['image_url'] = $url;
        }

        $product->update($data);

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'product' => $product
        ], 200);
    }

    // ELIMINAR PRODUCTO
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        // Eliminar la imagen de Firebase si existe
        if ($product->image_url) {
            $this->firebaseService->deleteImage($product->image_url);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}