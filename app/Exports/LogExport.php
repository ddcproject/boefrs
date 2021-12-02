<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogExport extends Model
{
	use SoftDeletes;

	protected $table = 'log_export';
	protected $primaryKey = 'id';

	protected $fillable = [
		'ref_user_id',
		'pt_status',
		'start_date',
		'end_date',
		'file_name',
		'file_imme_type',
		'file_size',
		'export_amoung',
		'expire_date',
		'last_export_date'
	];

}
