<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pendingmail
 * 
 * @property int $id
 * @property string|null $subject
 * @property string|null $to
 * @property string|null $text
 * @property int|null $retry
 * @property Carbon|null $date
 *
 * @package App\Models
 */
class Pendingmail extends Model
{
	protected $table = 'pendingmail';
	public $timestamps = false;

	protected $casts = [
		'retry' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'subject',
		'to',
		'text',
		'retry',
		'date'
	];
}
