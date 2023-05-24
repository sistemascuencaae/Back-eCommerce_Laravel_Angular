<?php

namespace App\Models\Models\Product;

use App\Models\Models\Sale\Review\Review;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tittle",
        "categorie_id",
        "slug",
        "sku",
        "tags",
        "price_soles",
        "price_usd",
        "resumen",
        "description",
        "state",
        "imagen",
        "stock",
        "type_inventario",
    ];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Guayaquil");
        $this->attributes["created_at"] = Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set("America/Guayaquil");
        $this->attributes["updated_at"] = Carbon::now();
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class); //Relacion de uno a muchos
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class); //Relacion de uno a muchos
    }

    public function reviews()
    {
        return $this->hasMany(Review::class); //Relacion de uno a muchos
    }

    public function scopefilterProduct($query, $search, $categorie_id)
    {
        if ($search) {
            $query->where("tittle", "like", "%" . $search . "%");
        }
        if ($categorie_id) {
            $query->where("categorie_id", $categorie_id);
        }
        return $query;
    }

}