<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SexGroup extends Model
{


	protected $table = 'z_rp_sex';
	protected $fillable = [
		'year_result',
		'male',
		'female',
		'hos_prov',
		'totals',
	];
}
