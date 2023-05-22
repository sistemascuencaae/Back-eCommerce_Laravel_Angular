<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Models\Client\AddressUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'type_user',
        'state',
        'role_id',
        'email',
        'password',
    ];

    public function setPasswordAttribute($password)
    {
        if ($password) { //Solo si existe un password
            $this->attributes["password"] = bcrypt($password); //Sirve para encriptar el BCRYPT
        }

    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Relacion de uno a uno (usuario con el rol)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function address()
    {
        return $this->hasMany(AddressUser::class);
    }

    public function scopefilterAdvance($query, $state, $search)
    {
        if ($state) {
            $query->where("state", $state);
        }
        if ($search) {
            $query->where("name", "like", "%" . $search . "%")
                ->orWhere("surname", "like", "%" . $search . "%");
        }
        return $query;
    }

}