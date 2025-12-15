<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    // AGREGAR FAVORITO
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Verificar si el favorito ya existe
        $existingFavorite = Favorite::where('user_id', auth()->id())
                                    ->where('product_id', $request->product_id)
                                    ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Este producto ya está en favoritos'], 200);
        }

        $favorite = Favorite::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'message' => 'Producto agregado a favoritos',
            'favorite' => $favorite
        ], 201);
    }

    // LISTAR FAVORITOS DEL USUARIO
    public function index()
    {
        return Favorite::where('user_id', auth()->id())->with('product')->get();
    }

    // ELIMINAR FAVORITO
    public function destroy($id)
    {
        $favorite = Favorite::where('user_id', auth()->id())
                            ->where('id', $id)
                            ->first();

        if (!$favorite) {
            return response()->json(['error' => 'Favorito no encontrado'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Favorito eliminado']);
    }
}
