<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class SymptomsTableSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		App\Symptoms::truncate();
		$symptoms = [
			[
				'symptom_name_en' => 'feverish',
				'symptom_name_th' => 'ไข้'
			],
			[
				'symptom_name_en' => 'cough',
				'symptom_name_th' => 'ไอ'
			],
			[
				'symptom_name_en' => 'sore throat',
				'symptom_name_th' => 'เจ็บคอ'
			],
			[
				'symptom_name_en' => 'runny or stuffy nose',
				'symptom_name_th' => 'มีน้ำมูก/คัดจมูก'
			],
			[
				'symptom_name_en' => 'sputum',
				'symptom_name_th' => 'มีเสมหะ'
			],
			[
				'symptom_name_en' => 'headache',
				'symptom_name_th' => 'ปวดศรีษะ'
			],
			[
				'symptom_name_en'=>'myalgia',
				'symptom_name_th'=>'ปวดเมื่อยกล้ามเนื้อ'
			],
			[
				'symptom_name_en'=>'fatigue',
				'symptom_name_th'=>'อ่อนเพลีย'
			],
			[
				'symptom_name_en'=>'dyspnea',
				'symptom_name_th'=>'หอบเหนื่อย'
			],
			[
				'symptom_name_en'=>'tachypnea',
				'symptom_name_th'=>'หายใจเร็ว'
			],
			[
				'symptom_name_en'=>'wheezing',
				'symptom_name_th'=>'หายใจมีเสียงวี๊ด'
			],
			[
				'symptom_name_en'=>'conjunctivitis',
				'symptom_name_th'=>'เยื่อบุตาอักเสบ/ตาแดง'
			],
			[
				'symptom_name_en'=>'vomiting',
				'symptom_name_th'=>'อาเจียน'
			],
			[
				'symptom_name_en'=>'diarrhea',
				'symptom_name_th'=>'ท้องเสีย'
			],
			[
				'symptom_name_en'=>'Apnea',
				'symptom_name_th'=>'Apnea (เด็ก อายุ 0-6 เดือน)'
			],
			[
				'symptom_name_en'=>'Sesis',
				'symptom_name_th'=>'Sesis'
			],
			[
				'symptom_name_en'=>'Encephalitis',
				'symptom_name_th'=>'สมองอักเสบ'
			],
			[
				'symptom_name_en'=>'Endotracheal intubation',
				'symptom_name_th'=>'ใส่ท่อช่วยหายใจ'
			],
			[
				'symptom_name_en'=>'Pneumonia',
				'symptom_name_th'=>'ปอดอักเสบ/ปอดบวม'
			],
			[
				'symptom_name_en'=>'Kidney',
				'symptom_name_th'=>'ไตวาย'
			],
			[
				'symptom_name_en'=>'Other',
				'symptom_name_th'=>'อื่นๆ โปรดระบุ'
			],
		];
		App\Symptoms::insert($symptoms);
	}
}
