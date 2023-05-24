<?php

namespace App\Models\Models\Sale;

use App\Models\Models\Product\Product;
use App\Models\Models\Product\ProductColorSize;
use App\Models\Models\Product\ProductSize;
use App\Models\Models\Sale\Review\Review;
use App\Models\Models\Sale\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        "sale_id",
        "product_id",
        "type_discount",
        "discount",
        "cantidad",
        "product_size_id",
        "product_color_size_id",
        "code_cupon",
        "code_discount",
        "precio_unitario",
        "subtotal",
        "total",
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

    public function client()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_size()
    {
        return $this->belongsTo(ProductSize::class);
    }
    public function product_color_size()
    {
        return $this->belongsTo(ProductColorSize::class);
    }

    public function review()
    {
        //  return $this->belongsTo(Review::class); // El belongsTo se usa cuando tenemos en id de la otra tabla en en esta tabla SaleDetail
        return $this->hasOne(Review::class); //El hasOne se usa cuando tenemos en id de la otra tabla, esta en la tabla Original en este caso en la tabla Review
    }
}