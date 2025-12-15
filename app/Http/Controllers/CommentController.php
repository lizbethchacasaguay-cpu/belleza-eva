<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{
    // CREAR COMENTARIO (requiere autenticación)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'text' => 'required|string|max:200'  // Máximo 200 caracteres como se requiere
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'text' => $request->text
        ]);

        return response()->json(['message' => 'Comentario guardado'], 201);
    }

    // LISTAR COMENTARIOS POR PRODUCTO (público - sin autenticación)
    public function showByProduct($product_id)
    {
        $comments = Comment::where('product_id', $product_id)
                           ->with('user')
                           ->get()
                           ->map(function($comment) {
                               return [
                                   'id' => $comment->id,
                                   'user_name' => $comment->user->name,
                                   'user_id' => $comment->user_id,
                                   'text' => $comment->text,
                                   'product_id' => $comment->product_id,
                                   'created_at' => $comment->created_at
                               ];
                           });

        return response()->json($comments);
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
