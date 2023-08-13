<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DemandeTransfert
 *
 * @property int $id
 * @property int $solde_id
 * @property string $au_numero
 * @property float $montant
 * @property Carbon|null $date
 * @property string|null $status
 * @property string|null $note_validation
 * @property string|null $trans_id
 * @property Carbon|null $date_validation
 *
 * @property Solde $solde
 *
 * @package App\Models
 */
class DemandeTransfert extends Model
{
    protected $table = 'demande_transfert';
    public $timestamps = false;

    protected $casts = [
        'solde_id' => 'int',
        'montant' => 'float'
    ];

    protected $dates = [
        'date',
        'date_validation'
    ];

    protected $fillable = [
        'solde_id',
        'au_numero',
        'montant',
        'date',
        'status',
        'note_validation',
        'trans_id',
        'date_validation'
    ];

    public function solde()
    {
        return $this->belongsTo(Solde::class);
    }
}
