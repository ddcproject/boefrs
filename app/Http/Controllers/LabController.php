<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Patients;
use App\Clinical;
use App\Specimen;
use App\Lab;
use DB;
use Session;
use App\DataTables\PatientsDataTableForLab;

class LabController extends BoeFrsController
{
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware(['role:admin|hospital|lab|dmsc']);
		$this->middleware('page_session');
	}

	public function listToDatatable(PatientsDataTableForLab $dataTable) {
		return $dataTable->render('lab.list');
	}

	public function index() {
		$roleArr = auth()->user()->getRoleNames();
		switch ($roleArr[0]) {
			case 'admin':
				$patients = Patients::whereNull('deleted_at')->get();
				break;
			case 'lab':
				$hospcode = auth()->user()->hospcode;
				$patients = Patients::where('ref_user_hospcode', '=', $hospcode)->whereNull('deleted_at')->toSql();
				dd($patients);
				break;
			case 'dmsc':
				$patients = Patients::whereNull('deleted_at')->get();
				break;
			default:
				return redirect()->route('logout');
		}
		return view(
			'lab.index',
			[
				'titleName' => $this->title_name,
				'patients' => $patients
			]
		);
	}

	public function create(Request $request) {
		$user_hospital = parent::hospitalByCode(auth()->user()->hospcode);
		$specimen = parent::specimen()->keyBy('id')->toArray();
		$patient_specimen = Specimen::where('ref_pt_id', '=', $request->id)->get()->keyBy('id')->toArray();
		$pathogen = parent::pathogen()->keyBy('id');

		$patient = Patients::where('id', '=', $request->id)->get();
		if (count($patient) > 0) {
			if (!empty($patient[0]->hospital)) {
				$patient_hospital = parent::hospitalByCode($patient[0]->hospital);
			} else {
				$patient_hospital = null;
			}
		}
		$analyze_id = parent::randPin('L');
		return view('lab.create',
			[
				'user_hospital'=>$user_hospital,
				'specimen'=>$specimen,
				'pathogen'=>$pathogen,
				'patient'=>$patient,
				'patient_hospital'=>$patient_hospital,
				'patient_specimen'=>$patient_specimen,
				'analyze_id'=>$analyze_id

			]
		);

	}

	public function store(Request $request)
	{
		$data = $request->all();
		if (!isset($data['analyzeId']) || empty($data['analyzeId'])) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกหมายเลขวิเคราะห์ !!']);
			exit;
		} else {
			$analyze = Lab::where('analyze_id', '=', $data['analyzeId'])->first();
			if ($analyze != null) {
				return response()->json(['status'=>204, 'msg'=>'หมายเลขวิเคราะห์นี้มีอยู่ในระบบแล้ว !!']);
				exit;
			}
		}
		if (empty($data['receiveDate'])) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกข้อมูล วันที่รับตัวอย่าง !!']);
			exit;
		}
		if (empty($data['analyzeDateStart']) || empty($data['analyzeDateEnd'])   ) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกข้อมูล วันที่ทำการวิเคราะห์ !!']);
			exit;
		}
		if (empty($data['resultDate'])) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกข้อมูล วันที่รายงานผล !!']);
			exit;
		}
		if (!isset($data['pt_specimen'])) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกข้อมูล Laboratory !!']);
			exit;
		}
		if ($data['pt_specimen'][0] == 0 || $data['pathogen'][0] == 0) {
			return response()->json(['status'=>204, 'msg'=>'โปรดกรอกข้อมูลผลการตรวจ !!']);
			exit;
		}
		if (count($data['pt_specimen']) == count($data['pathogen'])) {
			DB::beginTransaction();
			try {
				$lab_data = array();
				for ($i=0; $i<count($data['pt_specimen']); $i++) {
					$lab['hospital'] = $data['patientHospitalCode'];
					$lab['analyze_id'] = $data['analyzeId'];
					$lab['receive_date'] = parent::convertDateToMySQL($data['receiveDate']);
					$lab['analyze_date_start'] = parent::convertDateToMySQL($data['analyzeDateStart']);
					$lab['analyze_date_end'] = parent::convertDateToMySQL($data['analyzeDateEnd']);
					$lab['result_date'] = parent::convertDateToMySQL($data['resultDate']);
					$lab['ref_patient_id'] = $data['patientId'];
					$lab['ref_specimen_id'] = $data['pt_specimen'][$i];
					$lab['ref_pathogen_id'] = $data['pathogen'][$i];
					$lab['ref_user_id'] = $data['userIdInput'];
					$lab['pathogen_strain'] = $data['pathogen_strain'][$i];
					$lab['pathogen_note'] = $data['pathogen_note'][$i];
					$lab['created_at'] = date('Y-m-d H:i:s');
					$lab['updated_at'] = date('Y-m-d H:i:s');
					array_push($lab_data, $lab);
				}
				$lab_saved = Lab::insert($lab_data);

				$patient = Patients::find($data['patientId']);
				$patient->lab_status = 'updated';
				$patient_saved = $patient->save();
				DB::commit();
				if ($lab_saved) {
					$message = ['status'=>200, 'msg'=>'บันทึกข้อมูลสำเร็จแล้ว', 'title'=>'Flu Right Site'];
				} else {
					DB::rollback();
					$message = ['status'=>500, 'msg'=>'Internal Server Error! Something Went Wrong!', 'title'=>'Flu Right Site'];
				}
			} catch (Exception $e) {
				DB::rollback();
				$message = ['status'=>500, 'msg'=>$e->getMessage(), 'title'=>'Flu Right Site'];
			}
			return response()->json($message);
		} else {
			return response()->json(['status'=>500, 'msg'=>'โปรดตรวจการกรอกสอบข้อมูล', 'title'=>'Flu Right Site']);
			exit;
		}
	}

	public function show($id) {
		/* prepare data */
		$titleName = parent::titleName();
		$title_name = $titleName->keyBy('id');
		$provinces = parent::provinceListArr();
		$symptoms = parent::symptoms();
		$specimen = parent::specimen();
		$specimen = $specimen->keyBy('id');
		$pathogen = parent::pathogen();
		$pathogen = $pathogen->keyBy('id');

		/* get patient */
		$patient = Patients::where('id', '=', $id)
		->whereNull('deleted_at')
		->get();

		/* get patient clinical */
		$clinical = Clinical::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get();

		/* get patient specimen */
		$patient_specimen = Specimen::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get()->keyBy('specimen_type_id');

		$specimen_rs = collect();
		$rs = $specimen->each(function($item, $key) use ($specimen_rs, $patient_specimen) {
			$tmp['rs_id'] = $item->id;
			$tmp['rs_name_en'] = $item->name_en;
			$tmp['rs_name_th'] = $item->name_th;
			$tmp['rs_abbreviation'] = $item->abbreviation;
			$tmp['rs_note'] = $item->note;
			$tmp['rs_other_field'] = $item->other_field;
			if (count($patient_specimen) > 0) {
				foreach ($patient_specimen as $k => $v) {
					if ($v['specimen_type_id'] == $item->id) {
						$tmp['s_id'] = $v['id'];
						$tmp['s_ref_pt_id'] = $v['ref_pt_id'];
						$tmp['s_specimen_id'] = $v['specimen_type_id'];
						$tmp['s_specimen_other'] = $v['specimen_other'];
						$tmp['s_specimen_date'] = parent::convertMySQLDateFormat($v['specimen_date']);
						$tmp['s_specimen_result'] = $v['specimen_result'];
						$tmp['s_ref_user_id'] = $v['ref_user_id'];
						$tmp['s_created_at'] = $v['created_at'];
						$tmp['s_updated_at'] = $v['updated_at'];
						$tmp['s_deleted_at'] = $v['deleted_at'];
						break;
					} else {
						$tmp['s_id'] = null;
						$tmp['s_ref_pt_id'] = null;
						$tmp['s_specimen_id'] = null;
						$tmp['s_specimen_other'] = null;
						$tmp['s_specimen_date'] = null;
						$tmp['s_specimen_result'] = null;
						$tmp['s_ref_user_id'] = null;
						$tmp['s_created_at'] = null;
						$tmp['s_updated_at'] = null;
						$tmp['s_deleted_at'] = null;
					}
				}
			}
			$specimen_rs->put($item->id, $tmp);
		});
		$specimen_rs->all();

		/* get patient lab result */
		$patient_lab = Lab::where('ref_patient_id', $id)
			->whereNull('deleted_at')
			->get();
		$patient_lab = $patient_lab->toArray();

		/* *** set data to array *** */
		/* user full name */
		$utn_key = auth()->user()->title_name;
		if (empty($utn_key) || is_null($utn_key) || $utn_key == '') {
			$utn = null;
		} else {
			if ($utn_key == 6) {
				$utn = auth()->user()->title_name_other;
			} else {
				$utn = $title_name[$utn_key]->title_name;
			}
		}
		$uFullName = $utn.auth()->user()->name." ".auth()->user()->lastname;
		$data['user_fullname'] = $uFullName;

		/* user office */
		$user_office = parent::hospitalByCode(auth()->user()->hospcode);
		$uOffice = $user_office[0]->hosp_name;
		$data['user_office'] = $uOffice;

		/* user province */
		$uProvince = $provinces[auth()->user()->province]->province_name;
		$data['user_province'] = $uProvince;

		/* user phone/fax */
		$data['user_phone'] = auth()->user()->phone;
		$data['user_fax'] = auth()->user()->fax;

		/* patient data */
		$data['patient_id'] = $patient[0]->id;
		$data['patient_lab_code'] = $patient[0]->lab_code;

		if ($patient[0]->title_name == 6) {
			$ptn = $patient[0]->title_name_other;
		} else {
			$ptn = $title_name[$patient[0]->title_name]->title_name;
		}
		$pFullName = $ptn.$patient[0]->first_name." ".$patient[0]->last_name;
		$data['patient_fullname'] = $pFullName;
		$data['patient_gender'] = $patient[0]->gender;
		$data['patient_hn'] = $patient[0]->hn;
		$data['patient_an'] = $patient[0]->an;
		$data['patient_age'] = $patient[0]->age_year."-".$patient[0]->age_month."-".$patient[0]->age_day;
		$data['patient_house_no'] = $patient[0]->house_no;
		$data['patient_village_no'] = $patient[0]->village_no;
		$data['patient_village'] = $patient[0]->village;
		$data['patient_lane'] = $patient[0]->lane;

		if (!empty($patient[0]->province)) {
			$data['patient_province'] = $provinces[$patient[0]->province]->province_name;
		} else {
			$data['patient_province'] = null;
		}
		if (!empty($patient[0]->district)) {
			$patientDistrict = parent::districtById($patient[0]->district);
			$data['patient_district'] = $patientDistrict[0]->district_name;
		} else {
			$data['patient_district'] = null;
		}
		if (!empty($patient[0]->sub_district)) {
			$patientSubDistrict = parent::subDistrictById($patient[0]->sub_district);
			$data['patient_sub_district'] = $patientSubDistrict[0]->sub_district_name;
		} else {
			$data['patient_sub_district'] = null;
		}
		if (!empty($patient[0]->hospital)) {
			$patientHospital = parent::hospitalByCode($patient[0]->hospital);
			$data['patient_hospital'] = $patientHospital[0]->hosp_name;
		} else {
			$data['patient_hospital'] = null;
		}
		if (!empty($clinical[0]->date_sick)) {
			$data['patient_sickDate'] = parent::convertMySQLDateFormat($clinical[0]->date_sick, '/');
		} else {
			$data['patient_sickDate'] = null;
		}
		$data['patient_type'] = $clinical[0]->pt_type;
		if (!empty($clinical[0]->date_define)) {
			$data['patient_dateDefine'] = parent::convertMySQLDateFormat($clinical[0]->date_define, '/');
		} else {
			$data['patient_dateDefine'] = null;
		}
		$data['patient_temperature'] = $clinical[0]->pt_temperature;

		/* prepare sysmtom to array */
		$data['patient_fever_day'] = $clinical[0]->fever_day;
		$data['patient_fever_sym'] = $clinical[0]->fever_sym;
		$data['patient_cough_sym'] = $clinical[0]->cough_sym;
		$data['patient_sore_throat_sym'] = $clinical[0]->sore_throat_sym;
		$data['patient_runny_stuffy_sym'] = $clinical[0]->runny_stuffy_sym;
		$data['patient_sputum_sym'] = $clinical[0]->sputum_sym;
		$data['patient_headache_sym'] = $clinical[0]->headache_sym;
		$data['patient_myalgia_sym'] = $clinical[0]->myalgia_sym;
		$data['patient_fatigue_sym'] = $clinical[0]->fatigue_sym;
		$data['patient_dyspnea_sym'] = $clinical[0]->dyspnea_sym;
		$data['patient_tachypnea_sym'] = $clinical[0]->tachypnea_sym;
		$data['patient_wheezing_sym'] = $clinical[0]->wheezing_sym;
		$data['patient_conjunctivitis_sym'] = $clinical[0]->conjunctivitis_sym;
		$data['patient_vomiting_sym'] = $clinical[0]->vomiting_sym;
		$data['patient_diarrhea_sym'] = $clinical[0]->diarrhea_sym;
		$data['patient_apnea_sym'] = $clinical[0]->apnea_sym;
		$data['patient_sepsis_sym'] = $clinical[0]->sepsis_sym;
		$data['patient_encephalitis_sym'] = $clinical[0]->encephalitis_sym;
		$data['patient_intubation_sym'] = $clinical[0]->intubation_sym;
		$data['patient_pneumonia_sym'] = $clinical[0]->pneumonia_sym;
		$data['patient_kidney_sym'] = $clinical[0]->kidney_sym;
		$data['patient_other_sym'] = $clinical[0]->other_symptom;
		$data['patient_other_sym_text'] = $clinical[0]->other_symptom_specify;

		if ($clinical[0]->rapid_test == 'y') {
			$rapid_result_arr = explode(',', $clinical[0]->rapid_test_result);
		} else {
			$rapid_result_arr = array();
		}

		if (in_array('nagative', $rapid_result_arr)) {
			$data['patient_rapid_nagative'] = 'nagative';
		} else {
			$data['patient_rapid_nagative'] = null;
		}
		if (in_array('positive-flu-a', $rapid_result_arr)) {
			$data['patient_rapid_flu_a'] = 'positive-flu-a';
		} else {
			$data['patient_rapid_flu_a'] = null;
		}
		if (in_array('positive-flu-b', $rapid_result_arr)) {
			$data['patient_rapid_flu_b'] = 'positive-flu-b';
		} else {
			$data['patient_rapid_flu_b'] = null;
		}

		$data['patient_first_diag'] = $clinical[0]->first_diag;
		$data['patient_specimen'] = $specimen_rs;

		//dd($patient_lab);
		return view('lab.show',
			[
				'symptoms' => $symptoms,
				'specimen' => $specimen,
				'pathogen' => $pathogen,
				/*'specimen_data' => $specimen_data,*/
				'patient_lab' => $patient_lab,
				'data' => $data
			]
		);
	}

	public function show_old_1($id) {
		/* prepare data */
		$titleName = parent::titleName();
		$title_name = $titleName->keyBy('id');
		$provinces = parent::provinceListArr();
		$symptoms = parent::symptoms();
		$specimen = parent::specimen();
		$specimen = $specimen->keyBy('id');
		$pathogen = parent::pathogen();
		$pathogen = $pathogen->keyBy('id');

		/* get patient */
		$patient = Patients::where('id', '=', $id)
		->where('lab_status', '!=', 'new')
		->whereNull('deleted_at')
		->get();

		/* get patient clinical */
		$clinical = Clinical::where('ref_pt_id', $patient[0]->id)
		->whereNull('deleted_at')
		->get();

		/* get patient specimen */
		$specimen_data = Specimen::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get();
		$specimen_data = $specimen_data->keyBy('specimen_id');
		$specimen_rs = collect();
		$rs = $specimen->each(function($item, $key) use ($specimen_rs, $specimen_data) {
			$tmp['rs_id'] = $item->id;
			$tmp['rs_name_en'] = $item->name_en;
			$tmp['rs_name_th'] = $item->name_th;
			$tmp['rs_abbreviation'] = $item->abbreviation;
			$tmp['rs_note'] = $item->note;
			$tmp['rs_other_field'] = $item->other_field;
			if (count($specimen_data) > 0) {
				foreach ($specimen_data as $k => $v) {
					if ($v['specimen_id'] == $item->id) {
						$tmp['s_id'] = $v['id'];
						$tmp['s_ref_pt_id'] = $v['ref_pt_id'];
						$tmp['s_specimen_id'] = $v['specimen_id'];
						$tmp['s_specimen_other'] = $v['specimen_other'];
						$tmp['s_specimen_date'] = parent::convertMySQLDateFormat($v['specimen_date']);
						$tmp['s_specimen_result'] = $v['specimen_result'];
						$tmp['s_ref_user_id'] = $v['ref_user_id'];
						$tmp['s_created_at'] = $v['created_at'];
						$tmp['s_updated_at'] = $v['updated_at'];
						$tmp['s_deleted_at'] = $v['deleted_at'];
						break;
					} else {
						$tmp['s_id'] = null;
						$tmp['s_ref_pt_id'] = null;
						$tmp['s_specimen_id'] = null;
						$tmp['s_specimen_other'] = null;
						$tmp['s_specimen_date'] = null;
						$tmp['s_specimen_result'] = null;
						$tmp['s_ref_user_id'] = null;
						$tmp['s_created_at'] = null;
						$tmp['s_updated_at'] = null;
						$tmp['s_deleted_at'] = null;
					}
				}
			}
			$specimen_rs->put($item->id, $tmp);
		});
		$specimen_rs->all();

		/* get patient lab result */
		$patient_lab = Lab::where('ref_patient_id', $patient[0]->id)
			->whereNull('deleted_at')
			->get();
		$patient_lab = $patient_lab->toArray();

		/* *** set data to array *** */
		/* user full name */
		$utn_key = auth()->user()->title_name;
		if ($utn_key == 6) {
			$utn = auth()->user()->title_name_other;
		} else {
			$utn = $titleName[$utn_key]->title_name;
		}
		$uFullName = $utn.auth()->user()->name." ".auth()->user()->lastname;
		$data['user_fullname'] = $uFullName;

		/* user office */
		$user_office = parent::hospitalByCode(auth()->user()->hospcode);
		$uOffice = $user_office[0]->hosp_name;
		$data['user_office'] = $uOffice;

		/* user province */
		$uProvince = $provinces[auth()->user()->province]->province_name;
		$data['user_province'] = $uProvince;

		/* user phone/fax */
		$data['user_phone'] = auth()->user()->phone;
		$data['user_fax'] = auth()->user()->fax;

		/* patient data */
		$data['patient_id'] = $patient[0]->id;
		$data['patient_lab_code'] = $patient[0]->lab_code;

		if ($patient[0]->title_name == 6) {
			$ptn = $patient[0]->title_name_other;
		} else {
			$ptn = $titleName[$patient[0]->title_name]->title_name;
		}
		$pFullName = $ptn.$patient[0]->first_name." ".$patient[0]->last_name;
		$data['patient_fullname'] = $pFullName;
		$data['patient_gender'] = $patient[0]->gender;
		$data['patient_hn'] = $patient[0]->hn;
		$data['patient_an'] = $patient[0]->an;
		$data['patient_age'] = $patient[0]->age_year."-".$patient[0]->age_month."-".$patient[0]->age_day;
		$data['patient_house_no'] = $patient[0]->house_no;
		$data['patient_village_no'] = $patient[0]->village_no;
		$data['patient_village'] = $patient[0]->village;
		$data['patient_lane'] = $patient[0]->lane;
		$data['patient_province'] = $provinces[$patient[0]->province]->province_name;

		$patientDistrict = parent::districtById($patient[0]->district);
		$data['patient_district'] = $patientDistrict[0]->district_name;
		$patientSubDistrict = parent::subDistrictById($patient[0]->sub_district);
		$data['patient_sub_district'] = $patientSubDistrict[0]->sub_district_name;
		$data['patient_sickDate'] = parent::convertMySQLDateFormat($clinical[0]->date_sick, '/');
		$patientHospital = parent::hospitalByCode($patient[0]->hospital);
		$data['patient_hospital'] = $patientHospital[0]->hosp_name;

		$data['patient_type'] = $clinical[0]->pt_type;
		$data['patient_dateDefine'] = parent::convertMySQLDateFormat($clinical[0]->date_define, '/');
		$data['patient_temperature'] = $clinical[0]->pt_temperature;

		/* prepare sysmtom to array */
		$data['patient_fever_day'] = $clinical[0]->fever_day;
		$data['patient_fever_sym'] = $clinical[0]->fever_sym;
		$data['patient_cough_sym'] = $clinical[0]->cough_sym;
		$data['patient_sore_throat_sym'] = $clinical[0]->sore_throat_sym;
		$data['patient_runny_stuffy_sym'] = $clinical[0]->runny_stuffy_sym;
		$data['patient_sputum_sym'] = $clinical[0]->sputum_sym;
		$data['patient_headache_sym'] = $clinical[0]->headache_sym;
		$data['patient_myalgia_sym'] = $clinical[0]->myalgia_sym;
		$data['patient_fatigue_sym'] = $clinical[0]->fatigue_sym;
		$data['patient_dyspnea_sym'] = $clinical[0]->dyspnea_sym;
		$data['patient_tachypnea_sym'] = $clinical[0]->tachypnea_sym;
		$data['patient_wheezing_sym'] = $clinical[0]->wheezing_sym;
		$data['patient_conjunctivitis_sym'] = $clinical[0]->conjunctivitis_sym;
		$data['patient_vomiting_sym'] = $clinical[0]->vomiting_sym;
		$data['patient_diarrhea_sym'] = $clinical[0]->diarrhea_sym;
		$data['patient_apnea_sym'] = $clinical[0]->apnea_sym;
		$data['patient_sepsis_sym'] = $clinical[0]->sepsis_sym;
		$data['patient_encephalitis_sym'] = $clinical[0]->encephalitis_sym;
		$data['patient_intubation_sym'] = $clinical[0]->intubation_sym;
		$data['patient_pneumonia_sym'] = $clinical[0]->pneumonia_sym;
		$data['patient_kidney_sym'] = $clinical[0]->kidney_sym;
		$data['patient_other_sym'] = $clinical[0]->other_symptom;
		$data['patient_other_sym_text'] = $clinical[0]->other_symptom_specify;

		if ($clinical[0]->rapid_test == 'y') {
			$rapid_result_arr = explode(',', $clinical[0]->rapid_test_result);
		} else {
			$rapid_result_arr = array();
		}
		if (in_array('nagative', $rapid_result_arr)) {
			$data['patient_rapid_nagative'] = 'nagative';
		} else {
			$data['patient_rapid_nagative'] = null;
		}
		if (in_array('positive-flu-a', $rapid_result_arr)) {
			$data['patient_rapid_flu_a'] = 'positive-flu-a';
		} else {
			$data['patient_rapid_flu_a'] = null;
		}
		if (in_array('positive-flu-b', $rapid_result_arr)) {
			$data['patient_rapid_flu_b'] = 'positive-flu-b';
		} else {
			$data['patient_rapid_flu_b'] = null;
		}

		$data['patient_first_diag'] = $clinical[0]->first_diag;
		$data['patient_specimen'] = $specimen_rs;

		//dd($patient_lab);
		return view('patients.show',
			[
				'symptoms' => $symptoms,
				'specimen' => $specimen,
				'pathogen' => $pathogen,
				/*'specimen_data' => $specimen_data,*/
				'patient_lab' => $patient_lab,
				'data' => $data
			]
		);
	}

	public function show_preprint($id)
	{
		/* prepare data */
		$titleName = parent::titleName();
		$title_name = $titleName->keyBy('id');
		$provinces = parent::provinceListArr();
		$symptoms = parent::symptoms();
		$specimen = parent::specimen();
		$specimen = $specimen->keyBy('id');
		$pathogen = parent::pathogen();
		$pathogen = $pathogen->keyBy('id');

		/* get patient */
		$patient = Patients::where('id', '=', $id)
		->whereNull('deleted_at')
		->get();

		/* get patient clinical */
		$clinical = Clinical::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get();

		/* get patient specimen */
		$patient_specimen = Specimen::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get()->keyBy('specimen_type_id');

		$specimen_rs = collect();
		$rs = $specimen->each(function($item, $key) use ($specimen_rs, $patient_specimen) {
			$tmp['rs_id'] = $item->id;
			$tmp['rs_name_en'] = $item->name_en;
			$tmp['rs_name_th'] = $item->name_th;
			$tmp['rs_abbreviation'] = $item->abbreviation;
			$tmp['rs_note'] = $item->note;
			$tmp['rs_other_field'] = $item->other_field;
			if (count($patient_specimen) > 0) {
				foreach ($patient_specimen as $k => $v) {
					if ($v['specimen_type_id'] == $item->id) {
						$tmp['s_id'] = $v['id'];
						$tmp['s_ref_pt_id'] = $v['ref_pt_id'];
						$tmp['s_specimen_id'] = $v['specimen_type_id'];
						$tmp['s_specimen_other'] = $v['specimen_other'];
						$tmp['s_specimen_date'] = parent::convertMySQLDateFormat($v['specimen_date']);
						$tmp['s_specimen_result'] = $v['specimen_result'];
						$tmp['s_ref_user_id'] = $v['ref_user_id'];
						$tmp['s_created_at'] = $v['created_at'];
						$tmp['s_updated_at'] = $v['updated_at'];
						$tmp['s_deleted_at'] = $v['deleted_at'];
						break;
					} else {
						$tmp['s_id'] = null;
						$tmp['s_ref_pt_id'] = null;
						$tmp['s_specimen_id'] = null;
						$tmp['s_specimen_other'] = null;
						$tmp['s_specimen_date'] = null;
						$tmp['s_specimen_result'] = null;
						$tmp['s_ref_user_id'] = null;
						$tmp['s_created_at'] = null;
						$tmp['s_updated_at'] = null;
						$tmp['s_deleted_at'] = null;
					}
				}
			}
			$specimen_rs->put($item->id, $tmp);
		});
		$specimen_rs->all();

		/* get patient lab result */
		$patient_lab = Lab::where('ref_patient_id', $id)
			->whereNull('deleted_at')
			->get();
		$patient_lab = $patient_lab->toArray();

		/* *** set data to array *** */
		/* user full name */
		$utn_key = auth()->user()->title_name;
		if ($utn_key == 6) {
			$utn = auth()->user()->title_name_other;
		} else {
			$utn = $titleName[$utn_key]->title_name;
		}
		$uFullName = $utn.auth()->user()->name." ".auth()->user()->lastname;
		$data['user_fullname'] = $uFullName;

		/* user office */
		$user_office = parent::hospitalByCode(auth()->user()->hospcode);
		$uOffice = $user_office[0]->hosp_name;
		$data['user_office'] = $uOffice;

		/* user province */
		$uProvince = $provinces[auth()->user()->province]->province_name;
		$data['user_province'] = $uProvince;

		/* user phone/fax */
		$data['user_phone'] = auth()->user()->phone;
		$data['user_fax'] = auth()->user()->fax;

		/* patient data */
		$data['patient_id'] = $patient[0]->id;
		$data['patient_lab_code'] = $patient[0]->lab_code;

		if ($patient[0]->title_name == 6) {
			$ptn = $patient[0]->title_name_other;
		} else {
			$ptn = $titleName[$patient[0]->title_name]->title_name;
		}
		$pFullName = $ptn.$patient[0]->first_name." ".$patient[0]->last_name;
		$data['patient_fullname'] = $pFullName;
		$data['patient_gender'] = $patient[0]->gender;
		$data['patient_hn'] = $patient[0]->hn;
		$data['patient_an'] = $patient[0]->an;
		$data['patient_age'] = $patient[0]->age_year."-".$patient[0]->age_month."-".$patient[0]->age_day;
		$data['patient_house_no'] = $patient[0]->house_no;
		$data['patient_village_no'] = $patient[0]->village_no;
		$data['patient_village'] = $patient[0]->village;
		$data['patient_lane'] = $patient[0]->lane;

		if (!empty($patient[0]->province)) {
			$data['patient_province'] = $provinces[$patient[0]->province]->province_name;
		} else {
			$data['patient_province'] = null;
		}
		if (!empty($patient[0]->district)) {
			$patientDistrict = parent::districtById($patient[0]->district);
			$data['patient_district'] = $patientDistrict[0]->district_name;
		} else {
			$data['patient_district'] = null;
		}
		if (!empty($patient[0]->sub_district)) {
			$patientSubDistrict = parent::subDistrictById($patient[0]->sub_district);
			$data['patient_sub_district'] = $patientSubDistrict[0]->sub_district_name;
		} else {
			$data['patient_sub_district'] = null;
		}
		if (!empty($patient[0]->hospital)) {
			$patientHospital = parent::hospitalByCode($patient[0]->hospital);
			$data['patient_hospital'] = $patientHospital[0]->hosp_name;
		} else {
			$data['patient_hospital'] = null;
		}
		if (!empty($clinical[0]->date_sick)) {
			$data['patient_sickDate'] = parent::convertMySQLDateFormat($clinical[0]->date_sick, '/');
		} else {
			$data['patient_sickDate'] = null;
		}
		$data['patient_type'] = $clinical[0]->pt_type;
		if (!empty($clinical[0]->date_define)) {
			$data['patient_dateDefine'] = parent::convertMySQLDateFormat($clinical[0]->date_define, '/');
		} else {
			$data['patient_dateDefine'] = null;
		}
		$data['patient_temperature'] = $clinical[0]->pt_temperature;

		/* prepare sysmtom to array */
		$data['patient_fever_day'] = $clinical[0]->fever_day;
		$data['patient_fever_sym'] = $clinical[0]->fever_sym;
		$data['patient_cough_sym'] = $clinical[0]->cough_sym;
		$data['patient_sore_throat_sym'] = $clinical[0]->sore_throat_sym;
		$data['patient_runny_stuffy_sym'] = $clinical[0]->runny_stuffy_sym;
		$data['patient_sputum_sym'] = $clinical[0]->sputum_sym;
		$data['patient_headache_sym'] = $clinical[0]->headache_sym;
		$data['patient_myalgia_sym'] = $clinical[0]->myalgia_sym;
		$data['patient_fatigue_sym'] = $clinical[0]->fatigue_sym;
		$data['patient_dyspnea_sym'] = $clinical[0]->dyspnea_sym;
		$data['patient_tachypnea_sym'] = $clinical[0]->tachypnea_sym;
		$data['patient_wheezing_sym'] = $clinical[0]->wheezing_sym;
		$data['patient_conjunctivitis_sym'] = $clinical[0]->conjunctivitis_sym;
		$data['patient_vomiting_sym'] = $clinical[0]->vomiting_sym;
		$data['patient_diarrhea_sym'] = $clinical[0]->diarrhea_sym;
		$data['patient_apnea_sym'] = $clinical[0]->apnea_sym;
		$data['patient_sepsis_sym'] = $clinical[0]->sepsis_sym;
		$data['patient_encephalitis_sym'] = $clinical[0]->encephalitis_sym;
		$data['patient_intubation_sym'] = $clinical[0]->intubation_sym;
		$data['patient_pneumonia_sym'] = $clinical[0]->pneumonia_sym;
		$data['patient_kidney_sym'] = $clinical[0]->kidney_sym;
		$data['patient_other_sym'] = $clinical[0]->other_symptom;
		$data['patient_other_sym_text'] = $clinical[0]->other_symptom_specify;

		if ($clinical[0]->rapid_test == 'y') {
			$rapid_result_arr = explode(',', $clinical[0]->rapid_test_result);
		} else {
			$rapid_result_arr = array();
		}

		if (in_array('nagative', $rapid_result_arr)) {
			$data['patient_rapid_nagative'] = 'nagative';
		} else {
			$data['patient_rapid_nagative'] = null;
		}
		if (in_array('positive-flu-a', $rapid_result_arr)) {
			$data['patient_rapid_flu_a'] = 'positive-flu-a';
		} else {
			$data['patient_rapid_flu_a'] = null;
		}
		if (in_array('positive-flu-b', $rapid_result_arr)) {
			$data['patient_rapid_flu_b'] = 'positive-flu-b';
		} else {
			$data['patient_rapid_flu_b'] = null;
		}

		$data['patient_first_diag'] = $clinical[0]->first_diag;
		$data['patient_specimen'] = $specimen_rs;

		//dd($patient_lab);
		return view('printpdf.show',
			[
				'symptoms' => $symptoms,
				'specimen' => $specimen,
				'pathogen' => $pathogen,
				/*'specimen_data' => $specimen_data,*/
				'patient_lab' => $patient_lab,
				'data' => $data
			]
		);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show_old($id)
	{
		/* prepare data */
		$titleName = parent::titleName();
		$title_name = $titleName->keyBy('id');
		$provinces = parent::provinceListArr();
		$symptoms = parent::symptoms();
		$specimen = parent::specimen();
		$specimen = $specimen->keyBy('id');
		$pathogen = parent::pathogen();
		$pathogen = $pathogen->keyBy('id');

		/* get patient */
		$patient = Patients::where('id', '=', $id)
		->where('lab_status', '!=', 'new')
		->whereNull('deleted_at')
		->get();

		/* get patient clinical */
		$clinical = Clinical::where('ref_pt_id', $patient[0]->id)
		->whereNull('deleted_at')
		->get();

		/* get patient specimen */
		$specimen_data = Specimen::where('ref_pt_id', '=', $id)
		->whereNull('deleted_at')
		->get();
		$specimen_data = $specimen_data->keyBy('specimen_id');
		$specimen_rs = collect();
		$rs = $specimen->each(function($item, $key) use ($specimen_rs, $specimen_data) {
			$tmp['rs_id'] = $item->id;
			$tmp['rs_name_en'] = $item->name_en;
			$tmp['rs_name_th'] = $item->name_th;
			$tmp['rs_abbreviation'] = $item->abbreviation;
			$tmp['rs_note'] = $item->note;
			$tmp['rs_other_field'] = $item->other_field;
			if (count($specimen_data) > 0) {
				foreach ($specimen_data as $k => $v) {
					if ($v['specimen_id'] == $item->id) {
						$tmp['s_id'] = $v['id'];
						$tmp['s_ref_pt_id'] = $v['ref_pt_id'];
						$tmp['s_specimen_id'] = $v['specimen_id'];
						$tmp['s_specimen_other'] = $v['specimen_other'];
						$tmp['s_specimen_date'] = parent::convertMySQLDateFormat($v['specimen_date']);
						$tmp['s_specimen_result'] = $v['specimen_result'];
						$tmp['s_ref_user_id'] = $v['ref_user_id'];
						$tmp['s_created_at'] = $v['created_at'];
						$tmp['s_updated_at'] = $v['updated_at'];
						$tmp['s_deleted_at'] = $v['deleted_at'];
						break;
					} else {
						$tmp['s_id'] = null;
						$tmp['s_ref_pt_id'] = null;
						$tmp['s_specimen_id'] = null;
						$tmp['s_specimen_other'] = null;
						$tmp['s_specimen_date'] = null;
						$tmp['s_specimen_result'] = null;
						$tmp['s_ref_user_id'] = null;
						$tmp['s_created_at'] = null;
						$tmp['s_updated_at'] = null;
						$tmp['s_deleted_at'] = null;
					}
				}
			}
			$specimen_rs->put($item->id, $tmp);
		});
		$specimen_rs->all();

		/* *** set data to array *** */
		/* user full name */
		$utn_key = auth()->user()->title_name;
		if ($utn_key == 6) {
			$utn = auth()->user()->title_name_other;
		} else {
			$utn = $titleName[$utn_key]->title_name;
		}
		$uFullName = $utn.auth()->user()->name." ".auth()->user()->lastname;
		$data['user_fullname'] = $uFullName;

		/* user office */
		$user_office = parent::hospitalByCode(auth()->user()->hospcode);
		$uOffice = $user_office[0]->hosp_name;
		$data['user_office'] = $uOffice;

		/* user province */
		$uProvince = $provinces[auth()->user()->province]->province_name;
		$data['user_province'] = $uProvince;

		/* user phone/fax */
		$data['user_phone'] = auth()->user()->phone;
		$data['user_fax'] = auth()->user()->fax;

		/* patient data */
		$data['patient_id'] = $patient[0]->id;
		$data['patient_lab_code'] = $patient[0]->lab_code;

		if ($patient[0]->title_name == 6) {
			$ptn = $patient[0]->title_name_other;
		} else {
			$ptn = $titleName[$patient[0]->title_name]->title_name;
		}
		$pFullName = $ptn.$patient[0]->first_name." ".$patient[0]->last_name;
		$data['patient_fullname'] = $pFullName;

		$data['patient_gender'] = $patient[0]->gender;
		$data['patient_hn'] = $patient[0]->hn;
		$data['patient_age'] = $patient[0]->age_year."-".$patient[0]->age_month."-".$patient[0]->age_day;
		$data['patient_house_no'] = $patient[0]->house_no;
		$data['patient_village_no'] = $patient[0]->village_no;
		$data['patient_village'] = $patient[0]->village;
		$data['patient_lane'] = $patient[0]->lane;
		$data['patient_province'] = $provinces[$patient[0]->province]->province_name;

		$patientDistrict = parent::districtById($patient[0]->district);
		$data['patient_district'] = $patientDistrict[0]->district_name;

		$patientSubDistrict = parent::subDistrictById($patient[0]->sub_district);
		$data['patient_sub_district'] = $patientSubDistrict[0]->sub_district_name;

		$data['patient_sickDate'] = parent::convertMySQLDateFormat($clinical[0]->date_sick, '/');

		$patientHospital = parent::hospitalByCode($patient[0]->hospital);
		$data['patient_hospital'] = $patientHospital[0]->hosp_name;

		$data['patient_dateDefine'] = parent::convertMySQLDateFormat($clinical[0]->date_define, '/');
		$data['patient_temperature'] = $clinical[0]->pt_temperature;

		/* prepare sysmtom to array */
		$data['patient_fever_day'] = $clinical[0]->fever_day;
		$data['patient_fever_sym'] = $clinical[0]->fever_sym;
		$data['patient_cough_sym'] = $clinical[0]->cough_sym;
		$data['patient_sore_throat_sym'] = $clinical[0]->sore_throat_sym;
		$data['patient_runny_stuffy_sym'] = $clinical[0]->runny_stuffy_sym;
		$data['patient_sputum_sym'] = $clinical[0]->sputum_sym;
		$data['patient_headache_sym'] = $clinical[0]->headache_sym;
		$data['patient_myalgia_sym'] = $clinical[0]->myalgia_sym;
		$data['patient_fatigue_sym'] = $clinical[0]->fatigue_sym;
		$data['patient_dyspnea_sym'] = $clinical[0]->dyspnea_sym;
		$data['patient_tachypnea_sym'] = $clinical[0]->tachypnea_sym;
		$data['patient_wheezing_sym'] = $clinical[0]->wheezing_sym;
		$data['patient_conjunctivitis_sym'] = $clinical[0]->conjunctivitis_sym;
		$data['patient_vomiting_sym'] = $clinical[0]->vomiting_sym;
		$data['patient_diarrhea_sym'] = $clinical[0]->diarrhea_sym;
		$data['patient_apnea_sym'] = $clinical[0]->apnea_sym;
		$data['patient_sepsis_sym'] = $clinical[0]->sepsis_sym;
		$data['patient_encephalitis_sym'] = $clinical[0]->encephalitis_sym;
		$data['patient_intubation_sym'] = $clinical[0]->intubation_sym;
		$data['patient_pneumonia_sym'] = $clinical[0]->pneumonia_sym;
		$data['patient_kidney_sym'] = $clinical[0]->kidney_sym;
		$data['patient_other_sym'] = $clinical[0]->other_symptom;
		$data['patient_other_sym_text'] = $clinical[0]->other_symptom_specify;

		if ($clinical[0]->rapid_test == 'y') {
			$rapid_result_arr = explode(',', $clinical[0]->rapid_test_result);
		} else {
			$rapid_result_arr = array();
		}
		if (in_array('nagative', $rapid_result_arr)) {
			$data['patient_rapid_nagative'] = 'nagative';
		} else {
			$data['patient_rapid_nagative'] = null;
		}
		if (in_array('positive-flu-a', $rapid_result_arr)) {
			$data['patient_rapid_flu_a'] = 'positive-flu-a';
		} else {
			$data['patient_rapid_flu_a'] = null;
		}
		if (in_array('positive-flu-b', $rapid_result_arr)) {
			$data['patient_rapid_flu_b'] = 'positive-flu-b';
		} else {
			$data['patient_rapid_flu_b'] = null;
		}

		$data['patient_first_diag'] = $clinical[0]->first_diag;
		$data['patient_specimen'] = $specimen_rs;
		//dd($data['patient_specimen']);

		return view('lab.show',
			[
				'symptoms' => $symptoms,
				'specimen' => $specimen,
				'pathogen' => $pathogen,
				'specimen_data' => $specimen_data,
				'data' => $data
			]
		);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
	{
		return response()->json(['status'=>100, 'msg'=>'Comming soon !!']);
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
