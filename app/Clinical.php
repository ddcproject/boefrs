<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinical extends Model {
	use SoftDeletes;

	protected $table = 'clinical';
	protected $primaryKey = 'id';
	public $timestamps = true;
}
