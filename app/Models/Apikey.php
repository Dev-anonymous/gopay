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
 * @property int|null $active
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
		'users_id' => 'int',
		'active' => 'int'
	];

	protected $fillable = [
		'users_id',
		'key',
		'type',
		'active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}
}
