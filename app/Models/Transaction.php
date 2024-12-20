<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property int $id
 * @property int $compte_id
 * @property int|null $users_id
 * @property int|null $operateur_id
 * @property int $devise_id
 * @property float|null $montant
 * @property string|null $trans_id
 * @property string|null $type
 * @property string|null $source
 * @property string|null $data
 * @property Carbon|null $date
 * @property string|null $ref
 *
 * @property Compte $compte
 * @property Devise $devise
 * @property Operateur|null $operateur
 * @property User|null $user
 *
 * @package App\Models
 */
class Transaction extends Model
{
    protected $table = 'transaction';
    public $timestamps = false;

    protected $casts = [
        'compte_id' => 'int',
        'users_id' => 'int',
        'operateur_id' => 'int',
        'devise_id' => 'int',
        'montant' => 'float'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'compte_id',
        'users_id',
        'operateur_id',
        'devise_id',
        'montant',
        'trans_id',
        'type',
        'source',
        'data',
        'date',
        'ref'
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function operateur()
    {
        return $this->belongsTo(Operateur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
