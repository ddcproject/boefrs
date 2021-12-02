<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab extends Model
{
	use SoftDeletes;

	protected $table = 'lab';
	protected $primaryKey = 'id';
}
