<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property int|null $users_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $derniere_connexion
 * @property string|null $phone
 * @property string|null $avatar
 * @property string $user_role
 * @property string|null $pin
 * @property string|null $business_name
 * @property float $commission
 *
 * @property User|null $user
 * @property Collection|Apikey[] $apikeys
 * @property Collection|Compte[] $comptes
 * @property Collection|Recovery[] $recoveries
 * @property Collection|Transaction[] $transactions
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $casts = [
        'users_id' => 'int',
        'commission' => 'float'
    ];

    protected $dates = [
        'email_verified_at',
        'derniere_connexion'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'users_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'derniere_connexion',
        'phone',
        'avatar',
        'user_role',
        'pin',
        'business_name',
        'commission'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function apikeys()
    {
        return $this->hasMany(Apikey::class, 'users_id');
    }

    public function comptes()
    {
        return $this->hasMany(Compte::class, 'users_id');
    }

    public function recoveries()
    {
        return $this->hasMany(Recovery::class, 'users_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'users_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'users_id');
    }
}
