<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
	use SoftDeletes;

	protected $table = 'patients';
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'titleNameInput',
		'firstNameInput',
		'lastNameInput',
		'hnInput',
		'anInput'
	];
}
