<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patients extends Model
{
	use SoftDeletes;

	protected $table = 'patients';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id',
		'first_name',
		'last_name',
		'hn',
		'an'
	];
	public $timestamps = true;
}
