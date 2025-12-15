<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];

    // Relación con favoritos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relación con comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
