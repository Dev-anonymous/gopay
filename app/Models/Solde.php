<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Solde
 *
 * @property int $id
 * @property int $compte_id
 * @property int $devise_id
 * @property float|null $montant
 *
 * @property Compte $compte
 * @property Devise $devise
 * @property Collection|DemandeTransfert[] $demande_transferts
 *
 * @package App\Models
 */
class Solde extends Model
{
    protected $table = 'solde';
    public $timestamps = false;

    protected $casts = [
        'compte_id' => 'int',
        'devise_id' => 'int',
        'montant' => 'float'
    ];

    protected $fillable = [
        'compte_id',
        'devise_id',
        'montant'
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    public function demande_transferts()
    {
        return $this->hasMany(DemandeTransfert::class);
    }
}
