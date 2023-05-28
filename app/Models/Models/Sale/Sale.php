<?php

namespace App\Models\Models\Sale;

use App\Models\Models\Sale\SaleAddress;
use App\Models\Models\Sale\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "user_id",
        "method_payment",
        "currency_total",
        "currency_payment",
        "total",
        "price_dolar",
        "n_transaccion"
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale_address()
    {
        return $this->hasOne(SaleAddress::class);
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function scopefilterAdvance($query, $search, $categorie_id, $start_date, $end_date)
    {
        if ($search) {
            $query->whereHas("user", function ($q) use ($search) {
                $q->where("name", "like", "%" . $search . "%")->orWhere("surname", "like", "%" . $search . "%")->orWhere("email", "like", "%" . $search . "%");
            });
        }

        if ($categorie_id) {
            $query->whereHas("sale_details", function ($q) use ($categorie_id) {
                $q->whereHas("product", function ($subq) use ($categorie_id) {
                    $subq->where("categorie_id", $categorie_id);
                });
            });
        }

        if ($start_date && $end_date) {
            $query->whereBetween("created_at", [$start_date, $end_date]);
        }

        return $query;
    }
}