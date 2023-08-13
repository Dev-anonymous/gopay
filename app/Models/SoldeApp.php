<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SoldeApp
 * 
 * @property int $id
 * @property float|null $solde_cdf
 * @property float|null $solde_usd
 *
 * @package App\Models
 */
class SoldeApp extends Model
{
	protected $table = 'solde_app';
	public $timestamps = false;

	protected $casts = [
		'solde_cdf' => 'float',
		'solde_usd' => 'float'
	];

	protected $fillable = [
		'solde_cdf',
		'solde_usd'
	];
}
