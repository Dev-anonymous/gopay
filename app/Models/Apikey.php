<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Apikey
 * 
 * @property int $id
 * @property int $users_id
 * @property string $key
 * @property string $type
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Apikey extends Model
{
	protected $table = 'apikey';
	public $timestamps = false;

	protected $casts = [
		'users_id' => 'int'
	];

	protected $fillable = [
		'users_id',
		'key',
		'type'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}
}
