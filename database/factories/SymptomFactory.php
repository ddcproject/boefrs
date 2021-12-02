<?php
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Symptoms::class, function () {
	return [
		[
			'symptom_name_en' => 'feverish',
			'symptom_name_th' => 'ไข้',
			'symptom_status' => 'No',
		],
		[
			'symptom_name_en' => 'ทดสอบ',
			'symptom_name_th' => 'จัดไป',
			'symptom_status' => 'นะจ๊ะจ๋า',
		]
	];
});
