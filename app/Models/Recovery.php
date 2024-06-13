<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recovery
 * 
 * @property int $id
 * @property int $users_id
 * @property string|null $code
 * @property int|null $date
 * @property int|null $tentative
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Recovery extends Model
{
	protected $table = 'recovery';
	public $timestamps = false;

	protected $casts = [
		'users_id' => 'int',
		'date' => 'int',
		'tentative' => 'int'
	];

	protected $fillable = [
		'users_id',
		'code',
		'date',
		'tentative'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}
}
