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
 * @property string|null $au_numero
 * @property float|null $montant
 * @property Carbon|null $date
 * @property string|null $status
 * @property string|null $note_validation
 * 
 * @property Solde $solde
 *
 * @package App\Models
 */
class DemandeTransfert extends Model
{
	protected $table = 'demande_transfert';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'solde_id' => 'int',
		'montant' => 'float'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'solde_id',
		'au_numero',
		'montant',
		'date',
		'status',
		'note_validation'
	];

	public function solde()
	{
		return $this->belongsTo(Solde::class);
	}
}
