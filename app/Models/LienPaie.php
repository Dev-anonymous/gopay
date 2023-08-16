<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LienPaie
 * 
 * @property int $id
 * @property int $compte_id
 * @property string|null $nom
 * @property float|null $montant
 * @property string|null $devise
 * @property int|null $montant_fixe
 * @property int|null $devise_fixe
 * @property Carbon|null $date
 * 
 * @property Compte $compte
 *
 * @package App\Models
 */
class LienPaie extends Model
{
	protected $table = 'lien_paie';
	public $timestamps = false;

	protected $casts = [
		'compte_id' => 'int',
		'montant' => 'float',
		'montant_fixe' => 'int',
		'devise_fixe' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'compte_id',
		'nom',
		'montant',
		'devise',
		'montant_fixe',
		'devise_fixe',
		'date'
	];

	public function compte()
	{
		return $this->belongsTo(Compte::class);
	}
}
