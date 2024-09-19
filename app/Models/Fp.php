<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fp
 * 
 * @property int $id
 * @property string $user
 * @property string $cb_code
 * @property string $ref
 * @property string|null $myref
 * @property string $pay_data
 * @property int $is_saved
 * @property int $callback
 * @property int $transaction_was_failled
 * @property Carbon $date
 * @property string|null $notifyurl
 * @property int|null $cannotify
 * @property int|null $notifycount
 * @property string|null $notifypayload
 *
 * @package App\Models
 */
class Fp extends Model
{
	protected $table = 'fp';
	public $timestamps = false;

	protected $casts = [
		'is_saved' => 'int',
		'callback' => 'int',
		'transaction_was_failled' => 'int',
		'cannotify' => 'int',
		'notifycount' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'user',
		'cb_code',
		'ref',
		'myref',
		'pay_data',
		'is_saved',
		'callback',
		'transaction_was_failled',
		'date',
		'notifyurl',
		'cannotify',
		'notifycount',
		'notifypayload'
	];
}
