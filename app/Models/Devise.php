<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Devise
 * 
 * @property int $id
 * @property string|null $devise
 * 
 * @property Collection|Solde[] $soldes
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Devise extends Model
{
	protected $table = 'devise';
	public $timestamps = false;

	protected $fillable = [
		'devise'
	];

	public function soldes()
	{
		return $this->hasMany(Solde::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
