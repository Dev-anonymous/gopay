<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 * 
 * @property int $id
 * @property string $nom
 * @property string|null $telephone
 * @property string|null $email
 * @property string $sujet
 * @property string|null $message
 * @property Carbon $date
 *
 * @package App\Models
 */
class Feedback extends Model
{
	protected $table = 'feedback';
	public $timestamps = false;

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'nom',
		'telephone',
		'email',
		'sujet',
		'message',
		'date'
	];
}
