<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthMedian extends Model
{


	protected $table = 'z_rp_month_median';
	protected $fillable = [
		'year_result',
		'month_result',
		'hos_prov',
		'totals',
	];
}
