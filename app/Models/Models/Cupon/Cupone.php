<?php

namespace App\Models\Models\Cupon;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupone extends Model
{
    use HasFactory;
    use SoftDeletes; //Esta clase nos permite hacer un eliminado parcial

    protected $fillable = [
        "code",
        "type_discount",
        "discount",
        "type_count",
        "num_use",
        "state",
        "products",
        "categories"
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
}