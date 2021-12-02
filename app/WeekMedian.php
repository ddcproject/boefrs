<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekMedian extends Model
{
	protected $table = 'z_rp_week_median';
	protected $fillable = [
		'year_result',
		'week_result',
		'hos_prov',
		'totals',
	];
}
