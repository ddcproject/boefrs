<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\LogExport;
use App\Patients;
use App\TitleName;
use App\Nationality;
use App\Provinces;
use App\District;
use App\SubDistrict;
use App\Occupation;
use App\RefSpecimen;
use App\Traits\UserGroup;
use Carbon\Carbon;

class ExportsController extends BoeFrsController
{
	use UserGroup;

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware(['role:admin|hospital|lab|dmsc|hosp-group']);
		$this->middleware('page_session');
	}

	public function index() {
		return view('exports.csv');
	}

	private function setExportFileName($extension='csv') {
		$uid = auth()->user()->id;
		$current_timestamp = Carbon::now()->timestamp;
		$fileName = 'frs'.$uid.'-'.$current_timestamp.'.'.$extension;
		return $fileName;
	}

	private function setDateRange($date_range) {
		$exp = explode("/", $date_range);
		$result = $exp[2].'-'.$exp[0].'-'.$exp[1];
		return $result;
	}

	public function downloadFile($fileName=null) {
		try {
			$exists = Storage::disk('export')->exists($fileName);
			if ($exists) {
				$log = DB::table('log_export')->select('export_amount', 'expire_date')->where('file_name', '=', $fileName)->get()->toArray();
				$new_amount = ((int)$log[0]->export_amount+1);
				$now = date('Y-m-d H:i:s');
				$affected = DB::table('log_export')
					->where('file_name', $fileName)
					->update(['export_amount' => $new_amount, 'last_export_date' => $now]);
				$filePath = public_path('exports/'.$fileName);
				return response()->download($filePath);
			} else {
				return '<div>File not found.</div>';
			}
		} catch(Exception $e) {
			Log::error(sprintf("%s - line %d - ", __FILE__, __LINE__).$e->getMessage());
		}
	}

	private function getDistirctNameTh($dist_code='0') {
		try {
			$dist_name_arr = District::select('district_name')
				->where('district_id', '=', $dist_code)
				->get()
				->toArray();
			return $dist_name_arr;
		} catch (Exception $e) {
			Log::error($e->getMessage());
		}
	}

	private function getSubDistirctNameTh($sub_dist_code='0') {
		try {
			$sub_dist_name_arr = SubDistrict::select('sub_district_name')
				->where('sub_district_id', '=', $sub_dist_code)
				->get()
				->toArray();
			return $sub_dist_name_arr;
		} catch (Exception $e) {
			Log::error($e->getMessage());
		}
	}

	public function exportFastExcel(Request $request) {
		try {
			$fileName = self::setExportFileName();
			$exp_date = explode("-", $request->date_range);
			$start_date = $this->setDateRange(trim($exp_date[0]));
			$end_date = $this->setDateRange(trim($exp_date[1]));

			$roleArr = auth()->user()->roles->pluck('name');
			$user_role = $roleArr[0];
			$user = auth()->user()->id;
			$user_hosp = auth()->user()->hospcode;
			$title_name_arr = TitleName::all()->keyBy('id')->toArray();
			$nationalily_arr = Nationality::all()->keyBy('id')->toArray();
			$province_arr = Provinces::all()->keyBy('province_id')->toArray();
			$occupation_arr = Occupation::all()->keyBy('id')->toArray();
			$specimen_arr = RefSpecimen::all()->keyBy('id')->toArray();

			switch ($user_role) {
				case 'admin':
					$total = Patients::whereRaw("(DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')")->count();
					break;
				case 'hospital':
					$total = Patients::whereRaw("(DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->where('hospital', '=', $user_hosp)
						->orWhere('ref_user_hospcode', '=', $user_hosp)
						->count();
					break;
				case 'lab':
					$total = Patients::whereRaw("(DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->where('hospital', '=', $user_hosp)
						->orWhere('ref_user_hospcode', '=', $user_hosp)
						->count();
					break;
				case 'dmsc':
					$total = Patients::whereRaw("(DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')")->count();
					break;
				case 'hosp-group':
					$hospGroupArr = $this->getUserHospcodeToArr($user);
					$total = Patients::whereRaw("(DATE(created_at) BETWEEN '".$start_date."' AND '".$end_date."')")->whereIn('hospital', $hospGroupArr)->count();
					break;
				default:
					return redirect()->route('logout');
					break;
			}

			if ($total > 0) {
				(new FastExcel($this->dataGenerator($start_date, $end_date, $total)))->export('exports/'.$fileName, function($x) use ($title_name_arr, $nationalily_arr, $province_arr, $occupation_arr, $specimen_arr) {
					/* patient name */
					if (array_key_exists($x->title_name, $title_name_arr)) {
						if ($x->title_name != 6) {
							$titleName = $title_name_arr[$x->title_name]['title_name'];
						} else {
							$titleName = $x->title_name_other;
						}
					} else {
						$titleName = null;
					}
					$patientName = $titleName.$x->first_name." ".$x->last_name;

					/* nationality */
					if (array_key_exists($x->nationality, $nationalily_arr)) {
						if ($x->nationality != 11) {
							$nationalily = $nationalily_arr[$x->nationality]['name_th'];
						} else {
							$nationalily = $x->nationality_other;
						}
					} else {
						$nationalily = null;
					}

					/* province */
					if (array_key_exists($x->province, $province_arr)) {
						$prov_name = $province_arr[$x->province]['province_name'];
					} else {
						$prov_name = null;
					}

					/* district */
					if (empty($x->district) || $x->district == '0' || is_null($x->district)) {
						$dist_name = null;
					} else {
						$district_arr = self::getDistirctNameTh($x->district);
						if (count($district_arr) > 0) {
							$dist_name = $district_arr[0]['district_name'];
						} else {
							$dist_name = null;
						}
					}

					/* sub district */
					if (empty($x->sub_district) || $x->sub_district == '0' || is_null($x->sub_district)) {
						$sub_dist_name = null;
					} else {
						$sub_district_arr = self::getSubDistirctNameTh($x->sub_district);
						if (count($sub_district_arr) > 0) {
							$sub_dist_name = $sub_district_arr[0]['sub_district_name'];
						} else {
							$sub_dist_name = null;
						}
					}

					/* occupation */
					if ($x->occupation == 0 || empty($x->occupation) || is_null($x->occupation)) {
						$occupation_name = null;
					} else {
						if (array_key_exists($x->occupation, $occupation_arr)) {
							if ($x->occupation == 14) {
								$occupation_name = $x->occupation_other;
							} else {
								$occupation_name = $occupation_arr[$x->occupation]['occu_name_th'];
							}
						} else {
							$occupation_name = null;
						}
					}

					/* Specimen */
					if ($x->specimen_type_id == 0 || empty($x->specimen_type_id) || is_null($x->specimen_type_id)) {
						$specimen_name = null;
					} else {
						if (array_key_exists($x->specimen_type_id, $specimen_arr)) {
							if ($x->specimen_type_id == 9) {
								$specimen_name = $x->specimen_other;
							} else {
								$specimen_name = $specimen_arr[$x->specimen_type_id]['name_en'];
							}
						} else {
							$specimen_name = null;
						}
					}

					return [
						'Id' => $x->id,
						'ชื่อ-สกุล' => $patientName,
						'HN' => $x->hn,
						'AN' => $x->an,
						'เพศ' => $x->gender,
						'วันเกิด' => $x->date_of_birth,
						'อายุปี' => $x->age_year,
						'อายุเดือน' => $x->age_month,
						'อายุวัน' => $x->age_day,
						'สัญชาติ' => $nationalily,
						'โรงพยาบาล' => $x->hosp_name,
						'บ้านเลขที่' => $x->house_no,
						'หมู่' => $x->village_no,
						'หมู่บ้าน' => $x->village,
						'ตำบล' => $sub_dist_name,
						'อำเภอ' => $dist_name,
						'จังหวัด' => $prov_name,
						'อาชีพ' => $occupation_name,
						'รหัส' => $x->lab_code,
						'สถานะ รพ.' => $x->hosp_status,
						'สถานะ แลป' => $x->lab_status,
						'ประเภทผู้ป่วย' => $x->pt_type,
						'วันที่เริ่มป่วย' => $x->date_sick,
						'วันที่รักษาครั้งแรก' => $x->date_define,
						'วันที่นอนโรงพยาบาล' => $x->date_admit,
						'อุณหภูมิร่างกายแรกรับ' => $x->pt_temperature,
						'จำนวนวันที่ป่วย' => $x->fever_day,
						'ไข้' => $x->fever_sym,
						'ไอ' => $x->cough_sym,
						'เจ็บคอ' => $x->sore_throat_sym,
						'มีน้ำมูก/คัดจมูก' => $x->runny_stuffy_sym,
						'มีเสมหะ' => $x->sputum_sym,
						'ปวดศรีษะ ' => $x->headache_sym,
						'ปวดเมื่อยกล้ามเนื้อ ' => $x->myalgia_sym,
						'อ่อนเพลีย' => $x->fatigue_sym,
						'หอบเหนื่อย' => $x->dyspnea_sym,
						'หายใจเร็ว' => $x->tachypnea_sym,
						'หายใจมีเสียงวี๊ด' => $x->wheezing_sym,
						'เยื่อบุตาอักเสบ/ตาแดง' => $x->conjunctivitis_sym,
						'อาเจียน' => $x->vomiting_sym,
						'ท้องเสีย' => $x->diarrhea_sym,
						'Apnea' => $x->apnea_sym,
						'Sesis' => $x->sepsis_sym,
						'สมองอักเสบ' => $x->encephalitis_sym,
						'ใส่ท่อช่วยหายใจ' => $x->intubation_sym,
						'ปอดอักเสบ/ปอดบวม' => $x->pneumonia_sym,
						'ไตวาย' => $x->kidney_sym,
						'อาการอื่นๆ' => $x->other_symptom,
						'อาการอื่นๆ ระบุ' => $x->other_symptom_specify,
						'เอกซเรย์ปอด' => $x->lung,
						'วันที่เอกซเรย์ปอด' => $x->lung_date,
						'ผลเอกซเรย์ปอด' => $x->lung_result,
						'CBC' => $x->cbc_date,
						'HB' => $x->hb,
						'HTC' => $x->hct,
						'Plate count' => $x->platelet_count,
						'WBC' => $x->wbc,
						'N' => $x->n,
						'L' => $x->l,
						'Atyp lymph' => $x->atyp_lymph,
						'Mono' => $x->mono,
						'Baso' => $x->baso,
						'EO' => $x->eo,
						'Band' => $x->band,
						'การวินิจฉัยเบื้องต้น' => $x->first_diag,
						'Rapid test' => $x->rapid_test,
						'Rapid Test name' => $x->rapid_test_name,
						'ผล Rapid test' => $x->rapid_test_result,
						'เคยได้รับวัคซีนไข้หวัดใหญ่' => $x->flu_vaccine,
						'เคยได้รับเมื่อ' => $x->flu_vaccine_date,
						'การให้ยาต้านไวรัส' => $x->antiviral,
						'ชื่อยา' => $x->antiviral_name,
						'วันที่เริ่มให้ยา' => $x->antiviral_date,
						'หญิงตั้งครรภ์' => $x->pregnant,
						'อายุครรภ์' => $x->pregnant_wk,
						'หญิงหลังคลอด ในช่วง 2 สัปดาห์แรก' => $x->post_pregnant,
						'อ้วน' => $x->fat,
						'ส่วนสูง' => $x->fat_high,
						'น้ำหนัก' => $x->fat_weight,
						'เบาหวาน' => $x->diabetes,
						'ภูมิคุ้มกันบกพร่อง' => $x->immune,
						'ภูมิคุ้มกันบกพร่อง ระบุ' => $x->immune_specify,
						'คลอดก่อนกำหนด' => $x->early_birth,
						'อายุครรภ์คลอดก่อนกำหนด' => $x->early_birth_wk,
						'ภาวะทุพโภชนาการ' => $x->malnutrition,
						'โรคปอดเรื้อรัง' => $x->copd,
						'หอบหืด' => $x->asthma,
						'โรคหัวใจ' => $x->heart_disease,
						'โรคหลอดเลือกสมอง' => $x->cerebral,
						'โรคไตวาย' => $x->kidney_fail,
						'มะเร็ง' => $x->cancer,
						'มะเร็ง ระบุ' => $x->cancer_specify,
						'อื่นๆ' => $x->other_congenital,
						'อื่นๆ ระบุ' => $x->other_congenital_specify,
						'7 วันก่อนป่วยได้สัมผัสสัตว์ปีกป่วย/ตายโดยตรง' => $x->contact_poultry7,
						'14 วันก่อนป่วยได้สัมผัสสัตว์ป่วยโดยตรง' => $x->contact_poultry14,
						'ระบุชนิดสัตว์' => $x->contact_poultry14_specify,
						'14 วันก่อนป่วยได้พักอาศัยอยู่ในพื้นที่ที่มีสัตว์ปีกป่วย/ตายผิดปกติ' => $x->stay_poultry14,
						'14 วันก่อนป่วยได้พักอาศัยอยู่หรือเดินทางมาจากพื้นที่ที่ไข้หวัดใหญ่/ปอดอักเสบระบาด' => $x->stay_flu14,
						'ระบุพื้นที่' => $x->stay_flu14_place_specify,
						'14 วันก่อนป่วยได้ดูแลหรือสัมผัสใกล้ชิดกับผู้ป่วยอาการคล้ายไข้หวัดใหญ่/ปอดอักเสบ' => $x->contact_flu14,
						'14 วันก่อนป่วยไปเยี่ยมผู้ป่วยไข้หวัดใหญ่/ปอดอักเสบ' => $x->visit_flu14,
						'เป็นบุคลากรทางการแพทย์' => $x->health_care_worker,
						'เป็นผู้ป่วยสงสัยไข้หวัดใหญ่/ปอดอักเสบ ที่เข้ารับการรักษาเป็นกลุ่มก้อน' => $x->suspect_flu,
						'ประวัติเสี่ยงอื่นๆ' => $x->other_risk,
						'ระบุ' => $x->other_risk_specify,
						'ผลการรักษา' => $x->result_cli,
						'วันที่รายงาน' => $x->reported_at,
						'ตัวอย่างส่งตรวจ' => $specimen_name,
						'วันที่ส่งตรวจ' => $x->specimen_date,
						'ผลการตรวจ' => $x->specimen_result
					];
				});

				$fileExists = Storage::disk('export')->exists($fileName);
				if ($fileExists) {
					$mimetype = Storage::disk('export')->mimeType($fileName);
					$size = Storage::disk('export')->size($fileName);
					$size_kb = ((double)$size/1024);
					$expire_date = date('Y-m-d H:i:s', strtotime('+1 day'));

					$export = LogExport::create([
						'ref_user_id' => auth()->user()->id,
						'start_date' => $start_date,
						'end_date' => $end_date,
						'file_name' => $fileName,
						'file_imme_type' => $mimetype,
						'file_size' => $size_kb,
						'expire_date' => $expire_date
					]);

					$htm = "<ul style='list-style-type:none;margin:10px 0 0 0;padding:0'>";
					$htm .= "<li style='margin-bottom:8px;'><a href='".url("/getFile/{$fileName}")."' class='btn btn-danger btn-lg'>ดาวน์โหลดไฟล์ล่าสุดของคุณ คลิกที่นี่!!. </a></li>";
					$htm .= "<li>".number_format($size_kb, 2, '.', '')." KB, CSV</li>";
					$htm .= "</ul>";
					return $htm;
				} else {
					$htm = "<ul style='list-style-type:none;margin:10px 0 0 0;padding:0'>";
					$htm .= "<li>ไม่พบไฟล์ข้อมูล</li>";
					$htm .= "</ul>";
				}
			} else {
				$htm = "<ul style='list-style-type:none;margin:10px 0 0 0;padding:0'>";
				$htm .= "<li>ไม่พบข้อมูลตามเงื่อนไข</li>";
				$htm .= "</ul>";
			}
			return $htm;
		} catch(\Exception $e) {
			Log::error(sprintf("%s - line %d - ", __FILE__, __LINE__).$e->getMessage());
		}
	}

	public function dataGenerator($start_date, $end_date, $total) {
		try {
			$roleArr = auth()->user()->roles->pluck('name');
			$user_role = $roleArr[0];
			$user = auth()->user()->id;
			$user_hosp = auth()->user()->hospcode;

			$fields = array(
				'patients.id',
				'patients.title_name',
				'patients.title_name_other',
				'patients.first_name',
				'patients.last_name',
				'patients.hn',
				'patients.an',
				'patients.gender',
				'patients.date_of_birth',
				'patients.age_year',
				'patients.age_month',
				'patients.age_day',
				'patients.nationality',
				'patients.nationality_other',
				'hospitals.hosp_name',
				'patients.house_no',
				'patients.village_no',
				'patients.village',
				'patients.province',
				'patients.district',
				'patients.sub_district',
				'patients.occupation',
				'patients.occupation_other',
				'patients.lab_code',
				'patients.hosp_status',
				'patients.lab_status',
				'clinical.pt_type',
				'clinical.date_sick',
				'clinical.date_define',
				'clinical.date_admit',
				'clinical.pt_temperature',
				'clinical.fever_day',
				'clinical.fever_sym',
				'clinical.cough_sym',
				'clinical.sore_throat_sym',
				'clinical.runny_stuffy_sym',
				'clinical.sputum_sym',
				'clinical.headache_sym',
				'clinical.myalgia_sym',
				'clinical.fatigue_sym',
				'clinical.dyspnea_sym',
				'clinical.tachypnea_sym',
				'clinical.wheezing_sym',
				'clinical.conjunctivitis_sym',
				'clinical.vomiting_sym',
				'clinical.diarrhea_sym',
				'clinical.apnea_sym',
				'clinical.sepsis_sym',
				'clinical.encephalitis_sym',
				'clinical.intubation_sym',
				'clinical.pneumonia_sym',
				'clinical.kidney_sym',
				'clinical.other_symptom',
				'clinical.other_symptom_specify',
				'clinical.lung',
				'clinical.lung_date',
				'clinical.lung_result',
				'clinical.cbc_date',
				'clinical.hb',
				'clinical.hct',
				'clinical.platelet_count',
				'clinical.wbc',
				'clinical.n',
				'clinical.l',
				'clinical.atyp_lymph',
				'clinical.mono',
				'clinical.baso',
				'clinical.eo',
				'clinical.band',
				'clinical.first_diag',
				'clinical.rapid_test',
				'clinical.rapid_test_name',
				'clinical.rapid_test_result',
				'clinical.flu_vaccine',
				'clinical.flu_vaccine_date',
				'clinical.antiviral',
				'clinical.antiviral_name',
				'clinical.antiviral_date',
				'clinical.pregnant',
				'clinical.pregnant_wk',
				'clinical.post_pregnant',
				'clinical.fat',
				'clinical.fat_high',
				'clinical.fat_weight',
				'clinical.diabetes',
				'clinical.immune',
				'clinical.immune_specify',
				'clinical.early_birth',
				'clinical.early_birth_wk',
				'clinical.malnutrition',
				'clinical.copd',
				'clinical.asthma',
				'clinical.heart_disease',
				'clinical.cerebral',
				'clinical.kidney_fail',
				'clinical.cancer',
				'clinical.cancer_specify',
				'clinical.other_congenital',
				'clinical.other_congenital_specify',
				'clinical.contact_poultry7',
				'clinical.contact_poultry14',
				'clinical.contact_poultry14_specify',
				'clinical.stay_poultry14',
				'clinical.stay_flu14',
				'clinical.stay_flu14_place_specify',
				'clinical.contact_flu14',
				'clinical.visit_flu14',
				'clinical.health_care_worker',
				'clinical.suspect_flu',
				'clinical.other_risk',
				'clinical.other_risk_specify',
				'clinical.result_cli',
				'clinical.reported_at',
				'specimen.specimen_type_id',
				'specimen.specimen_other',
				'specimen.specimen_date',
				'specimen.specimen_result'
			);
			switch ($user_role) {
				case 'admin':
					foreach (Patients::select($fields)
						->whereRaw("(DATE(patients.created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->leftJoin('hospitals', 'patients.hospital', '=', 'hospitals.hospcode')
						->leftJoin('clinical', 'patients.id', '=', 'clinical.ref_pt_id')
						->leftJoin('specimen', 'patients.id', '=', 'specimen.ref_pt_id')
						->cursor() as $data) {
							yield $data;
					}
					break;
				case 'hospital':
					foreach (Patients::select($fields)
						->whereRaw("(DATE(patients.created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->where('patients.ref_user_hospcode', '=', $user_hosp)
						->orWhere('patients.hospital', '=', $user_hosp)
						->leftJoin('hospitals', 'patients.hospital', '=', 'hospitals.hospcode')
						->leftJoin('clinical', 'patients.id', '=', 'clinical.ref_pt_id')
						->leftJoin('specimen', 'patients.id', '=', 'specimen.ref_pt_id')
						->cursor() as $data) {
							yield $data;
					}
					break;
				case 'lab':
					foreach (Patients::select($fields)
						->whereRaw("(DATE(patients.created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->where('patients.ref_user_hospcode', '=', $user_hosp)
						->orWhere('patients.hospital', '=', $user_hosp)
						->leftJoin('hospitals', 'patients.hospital', '=', 'hospitals.hospcode')
						->leftJoin('clinical', 'patients.id', '=', 'clinical.ref_pt_id')
						->leftJoin('specimen', 'patients.id', '=', 'specimen.ref_pt_id')
						->cursor() as $data) {
							yield $data;
					}
					break;
				case 'dmsc':
					foreach (Patients::select($fields)
						->whereRaw("(DATE(patients.created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->leftJoin('hospitals', 'patients.hospital', '=', 'hospitals.hospcode')
						->leftJoin('clinical', 'patients.id', '=', 'clinical.ref_pt_id')
						->leftJoin('specimen', 'patients.id', '=', 'specimen.ref_pt_id')
						->cursor() as $data) {
							yield $data;
					}
					break;
				case 'hosp-group':
					$hospGroupArr = $this->getUserHospcodeToArr($user);
					foreach (Patients::select($fields)
						->whereRaw("(DATE(patients.created_at) BETWEEN '".$start_date."' AND '".$end_date."')")
						->whereIn('hospital', $hospGroupArr)
						->leftJoin('hospitals', 'patients.hospital', '=', 'hospitals.hospcode')
						->leftJoin('clinical', 'patients.id', '=', 'clinical.ref_pt_id')
						->leftJoin('specimen', 'patients.id', '=', 'specimen.ref_pt_id')
						->cursor() as $data) {
							yield $data;
					}
					break;
				default:
					return redirect()->route('logout');
					break;
			}
		} catch(\Exception $e) {
			Log::error(sprintf("%s - line %d - ", __FILE__, __LINE__).$e->getMessage());
		}
	}
}
