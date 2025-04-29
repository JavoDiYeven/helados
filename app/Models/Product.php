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
        'stock',
        'min_stock',
        'image',
        'category_id',
    ];

    /**
     * obtiene la categoría asociada al producto
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * chequea si el producto tiene bajo stock
     */
    public function hasLowStock()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * obtiene todos los productos de bajo stock
     */
    public static function getLowStockProducts()
    {
        return self::whereRaw('stock <= min_stock')->get();
    }
}
