<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBundleHosp extends Model
{
	protected $table = 'user_bundle_hosp';
	protected $primaryKey = 'id';

	public function user() {
		return $this->belongsTo(\App\User::class)->withDefault();
	}
}
