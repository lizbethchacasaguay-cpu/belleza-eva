<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{
    // CREAR COMENTARIO
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:200', 
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'text' => $request->comment
        ]);

        return response()->json([
            'message' => 'Comentario agregado con éxito',
            'comment' => $comment
        ], 201);
    }

    // LISTAR COMENTARIOS POR PRODUCTO
    public function showByProduct($product_id)
    {
        $comments = Comment::where('product_id', $product_id)
                           ->with('user')
                           ->get();

        return $comments;
    }

    // ELIMINAR COMENTARIO
    public function destroy($id)
    {
        $comment = Comment::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->first();

        if (!$comment) {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado']);
    }
}
