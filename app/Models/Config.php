<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * 
 * @property int $id
 * @property int $users_id
 * @property string $config
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Config extends Model
{
	protected $table = 'config';
	public $timestamps = false;

	protected $casts = [
		'users_id' => 'int'
	];

	protected $fillable = [
		'users_id',
		'config'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}
}
