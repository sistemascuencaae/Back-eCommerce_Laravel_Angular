<?php

namespace App\Models\Models\Discount;

use App\Models\Models\Discount\Discount;
use App\Models\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        "discount_id",
        "product_id",
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

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}