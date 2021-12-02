<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Provinces;
use App\District;
use App\SubDistrict;
use App\Occupation;
use App\Patients;
use App\Clinical;
use App\Specimen;
use App\Lab;
use App\User;

class PatientsController extends BoeFrsController
{
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware(['role:admin|hospital|lab|hosp-group']);
		$this->middleware('page_session');
	}
	protected function index(Request $request) {
		return $this->create($request);
	}
	protected function create(Request $request) {
		$patient = parent::patientsById($request->id);
		$nationality = parent::nationality()->keyBy('id');
		$occupation = Occupation::all()->keyBy('id')->toArray();
		$symptoms = parent::symptoms()->keyBy('id');
		$ref_specimen = parent::specimen()->keyBy('id');
		$user_hospital = parent::hospitalByCode(auth()->user()->hospcode);
		$hospital = parent::hospitalByBoeFrsActive()->keyBy('hospcode');
		$refGender = parent::getRefData('gender');

		$provinces = Provinces::all()->sortBy('province_name')->keyBy('province_id')->toArray();
		/* district */
		if (empty($patient[0]->district) || is_null($patient[0]->district) || $patient[0]->district == '0') {
			$district = null;
		} else {
			$district = District::whereDistrict_id($patient[0]->district)->get()->toArray();
			if (count($district) <= 0) {
				Log::warning('District field not match - uid:  '.auth()->user()->id.' - pid: '.$request->id);
			}
		}
		/* sub district */
		if (empty($patient[0]->sub_district) || is_null($patient[0]->sub_district) || $patient[0]->sub_district == '0') {
			$sub_district = null;
		} else {
			$sub_district = SubDistrict::whereSub_district_id($patient[0]->sub_district)->get()->toArray();
			if (count($sub_district) <= 0) {
				Log::warning('Sub_district field not match - uid: '.auth()->user()->id.' - pid: '.$request->id);
			}
		}
		/* get patient clinical */
		$clinical_query = Clinical::whereRef_pt_id($request->id)->get()->toArray();
		if (count($clinical_query) > 0) {
			$clinical = $clinical_query[0];
		} else {
			$clinical = null;
		}
		$data['date_of_birth'] = parent::convertMySQLDateFormat($patient[0]->date_of_birth) ?? null;
		$data['date_sick'] = parent::convertMySQLDateFormat($clinical['date_sick']) ?? null;
		$data['date_define'] = parent::convertMySQLDateFormat($clinical['date_define']) ?? null;
		$data['date_admit'] = parent::convertMySQLDateFormat($clinical['date_admit']) ?? null;
		$data['lung_date'] = parent::convertMySQLDateFormat($clinical['lung_date']) ?? null;
		$data['cbc_date'] = parent::convertMySQLDateFormat($clinical['cbc_date']) ?? null;
		$data['flu_vaccine_date'] = parent::convertMySQLDateFormat($clinical['flu_vaccine_date']) ?? null;
		$data['antiviral_date'] = parent::convertMySQLDateFormat($clinical['antiviral_date']) ?? null;
		$rapid_result = explode(',', $clinical['rapid_test_result']);
		/* get patient specimen */
		$specimen_query = Specimen::whereRef_pt_id($request->id)->get()->keyBy('specimen_type_id')->toArray();
		if (count($specimen_query) > 0) {
			$specimen = $specimen_query;
		} else {
			$specimen = null;
		}
		$specimen_data = collect();
		foreach ($ref_specimen as $key => $value) {
			$tmp['sp_id'] = $value->id;
			$tmp['sp_name_en'] = $value->name_en;
			$tmp['sp_name_th'] = $value->name_th;
			$tmp['sp_abbreviation'] = $value->abbreviation;
			$tmp['sp_note'] = $value->note;
			$tmp['sp_other_field'] = $value->other_field;
			if (isset($specimen[$value->id])) {
				$tmp['psp_id'] = $specimen[$value->id]['id'];
				$tmp['psp_ref_pt_id'] = $specimen[$value->id]['ref_pt_id'];
				$tmp['psp_specimen_id'] = $specimen[$value->id]['specimen_type_id'];
				$tmp['psp_specimen_other'] = $specimen[$value->id]['specimen_other'];
				$tmp['psp_specimen_date'] = parent::convertMySQLDateFormat($specimen[$value->id]['specimen_date']);
				$tmp['psp_specimen_result'] = $specimen[$value->id]['specimen_result'];
				$tmp['psp_ref_user_id'] = $specimen[$value->id]['ref_user_id'];
				$tmp['psp_created_at'] = $specimen[$value->id]['created_at'];
				$tmp['psp_updated_at'] = $specimen[$value->id]['updated_at'];
				$tmp['psp_deleted_at'] = $specimen[$value->id]['deleted_at'];
			} else {
				$tmp['psp_id'] = null;
				$tmp['psp_ref_pt_id'] = null;
				$tmp['psp_specimen_id'] = null;
				$tmp['psp_specimen_other'] = null;
				$tmp['psp_specimen_date'] = null;
				$tmp['psp_specimen_result'] = null;
				$tmp['psp_ref_user_id'] = null;
				$tmp['psp_created_at'] = null;
				$tmp['psp_updated_at'] = null;
				$tmp['psp_deleted_at'] = null;
			}
			$specimen_data->put($key, $tmp);
		}
		return view('patients.index', [
			'titleName' => $this->title_name,
			'refGender' => $refGender,
			'nationality' => $nationality,
			'occupation' => $occupation,
			'symptoms' => $symptoms,
			'user_hospital' => $user_hospital,
			'hospital' => $hospital,
			'patient' => $patient,
			'clinical' => $clinical,
			'specimen_data' => $specimen_data,
			'provinces' => $provinces,
			'district' => $district,
			'sub_district' => $sub_district,
			'data' => $data,
			'rapid_result' => $rapid_result
		]);
	}
	public function addPatient(Request $request) {
		/* find patient by id */
		$patient = Patients::find($request->pid);
		if (is_null($patient)) {
			$message = collect(['status'=>500, 'msg'=>'ไม่พบข้อมูลรหัสนี้ โปรดตรวจสอบ!', 'title'=>'Error!']);
			return redirect()->route('list-data.index')->with('message', $message);
		} else {
			/* validation */
			$this->validate($request, [
				'titleNameInput' => 'required',
				'firstNameInput' => 'required',
				'lastNameInput' => 'required',
				'hnInput' => 'required',
				'sexInput' => 'required',
				'hospitalInput' => 'required',
				'provinceInput' => 'required',
				'districtInput' => 'required',
				'subDistrictInput' => 'required',
				'patientType' => 'required',
				'sickDateInput' => 'required',
				'treatDateInput' => 'required'
			],[
				'titleNameInput.required' => 'Title name field is required.',
				'firstNameInput.required' => 'Firstname field is required',
				'lastNameInput.required' => 'Lastname field is required',
				'hnInput.required' => 'HN field is required',
				'sexInput.required' => 'Gender field is required.',
				'hospitalInput.required' => 'Hospital field is require',
				'provinceInput.required' => 'Province field is required',
				'districtInput.required' => 'District field is required',
				'subDistrictInput.required' => 'Sub-district field is required',
				'patientType.required' => 'Patient type field is required',
				'sickDateInput.required' => 'Sick date field is required',
				'treatDateInput.required' => 'Date define field is required'
			]);
			/* General section */
			if ($request->titleNameInput == -6) {
				$patient->title_name = 6;
			} else {
				$patient->title_name = $request->titleNameInput;
			}
			if (isset($request->otherTitleNameInput) && !empty($request->otherTitleNameInput)) {
				$patient->title_name_other = $request->otherTitleNameInput;
			}

			$patient->first_name = $request->firstNameInput;
			$patient->last_name = $request->lastNameInput;
			$patient->hn = $request->hnInput;
			$patient->an = $request->anInput;
			$patient->gender = $request->sexInput;
			$patient->date_of_birth = parent::convertDateToMySQL($request->birthDayInput);
			$patient->age_year = $request->ageYearInput;
			$patient->age_month = $request->ageMonthInput;
			$patient->age_day = $request->ageDayInput;
			$patient->nationality = $request->nationalityInput;

			if (isset($request->otherNationalityInput) && !empty($request->otherNationalityInput)) {
				$patient->nationality_other = $request->otherNationalityInput;
			}

			$patient->hospital = $request->hospitalInput;
			$patient->house_no = $request->houseNoInput;
			$patient->village_no = $request->villageNoInput;
			$patient->village = $request->villageInput;
			$patient->lane = $request->laneInput;
			$patient->province = $request->provinceInput;
			$patient->district = $request->districtInput;
			$patient->sub_district = $request->subDistrictInput;
			$patient->occupation = $request->occupationInput;

			if (isset($request->occupationOtherInput) && !empty($request->occupationOtherInput)) {
				$patient->occupation_other = $request->occupationOtherInput;
			}
			$patient->hosp_status = 'updated';

			/* Clinical section */
			$clinical = Clinical::where('ref_pt_id', '=', $request->pid)
				->whereNull('deleted_at')
				->first();
			if (is_null($clinical)) {
				$clinical = new Clinical;
			}
			$clinical->pt_type = $request->patientType;
			$clinical->date_sick = parent::convertDateToMySQL($request->sickDateInput);
			$clinical->date_define = parent::convertDateToMySQL($request->treatDateInput);
			$clinical->date_admit = parent::convertDateToMySQL($request->admitDateInput);
			$clinical->pt_temperature = $request->temperatureInput;
			$clinical->fever_sym = $request->symptom_1_Input;
			$clinical->cough_sym = $request->symptom_2_Input;
			$clinical->sore_throat_sym = $request->symptom_3_Input;
			$clinical->runny_stuffy_sym = $request->symptom_4_Input;
			$clinical->sputum_sym = $request->symptom_5_Input;
			$clinical->headache_sym = $request->symptom_6_Input;
			$clinical->myalgia_sym = $request->symptom_7_Input;
			$clinical->fatigue_sym = $request->symptom_8_Input;
			$clinical->dyspnea_sym = $request->symptom_9_Input;
			$clinical->tachypnea_sym = $request->symptom_10_Input;
			$clinical->wheezing_sym = $request->symptom_11_Input;
			$clinical->conjunctivitis_sym = $request->symptom_12_Input;
			$clinical->vomiting_sym = $request->symptom_13_Input;
			$clinical->diarrhea_sym = $request->symptom_14_Input;
			$clinical->apnea_sym = $request->symptom_15_Input;
			$clinical->sepsis_sym = $request->symptom_16_Input;
			$clinical->encephalitis_sym = $request->symptom_17_Input;
			$clinical->intubation_sym = $request->symptom_18_Input;
			$clinical->pneumonia_sym = $request->symptom_19_Input;
			$clinical->kidney_sym = $request->symptom_20_Input;
			$clinical->other_symptom = $request->symptom_21_Input;
			$clinical->other_symptom_specify = $request->other_symptom_input;
			$clinical->lung = $request->lungXrayInput;
			$clinical->lung_date = parent::convertDateToMySQL($request->xRayDateInput);
			$clinical->lung_result = $request->xRayResultInput;
			$clinical->cbc_date = parent::convertDateToMySQL($request->cbcDateInput);
			$clinical->hb = $request->hbInput;
			$clinical->hct = $request->htcInput;
			$clinical->platelet_count = $request->plateletInput;
			$clinical->wbc = $request->wbcInput;
			$clinical->n = $request->nInput;
			$clinical->l = $request->lInput;
			$clinical->atyp_lymph = $request->atypLymphInput;
			$clinical->mono = $request->monoInput;
			$clinical->baso = $request->basoInput;
			$clinical->eo = $request->eoInput;
			$clinical->band = $request->bandInput;
			$clinical->first_diag = $request->firstDiagnosisInput;
			$clinical->rapid_test = $request->influRapidInput;
			$clinical->rapid_test_name = $request->influRapidtestName;
			if ($request->has('rapidTestResultInput') && count($request->rapidTestResultInput) > 0) {
				$clinical->rapid_test_result = parent::arrToStr($request->rapidTestResultInput);
			} else {
				$clinical->rapid_test_result = null;
			}
			$clinical->flu_vaccine = $request->influVaccineInput;
			$clinical->flu_vaccine_date = parent::convertDateToMySQL($request->influVaccineDateInput);
			$clinical->antiviral = $request->virusMedicineInput;
			$clinical->antiviral_name = $request->medicineNameInput;
			$clinical->antiviral_date = parent::convertDateToMySQL($request->medicineGiveDateInput);
			$clinical->pregnant_wk = $request->pregnantWeekInput;
			$clinical->pregnant = $request->pregnantInput;
			$clinical->post_pregnant = $request->postPregnantInput;
			$clinical->fat_high = $request->fatHeightInput;
			$clinical->fat_weight = $request->fatWeightInput;
			$clinical->fat = $request->fatInput;
			$clinical->diabetes = $request->diabetesInput;
			$clinical->immune = $request->immuneInput;
			$clinical->immune_specify = $request->immuneSpecifyInput;
			$clinical->early_birth = $request->earlyBirthInput;
			$clinical->early_birth_wk = $request->earlyBirthWeekInput;
			$clinical->malnutrition = $request->malnutritionInput;
			$clinical->copd = $request->copdInput;
			$clinical->asthma = $request->asthmaInput;
			$clinical->heart_disease = $request->heartDiseaseInput;
			$clinical->cerebral = $request->cerebralInput;
			$clinical->kidney_fail = $request->kidneyFailInput;
			$clinical->cancer_specify = $request->cancerSpecifyInput;
			$clinical->cancer = $request->cancerInput;
			$clinical->other_congenital = $request->otherCongenitalInput;
			$clinical->other_congenital_specify = $request->otherCongenitalSpecifyInput;
			$clinical->contact_poultry7 = $request->contactPoultry7Input;
			$clinical->contact_poultry14 = $request->contactPoultry14Input;
			$clinical->contact_poultry14_specify = $request->contactPoultry14SpecifyInput;
			$clinical->stay_poultry14 = $request->stayPoultry14Input;
			$clinical->stay_flu14 = $request->stayFlu14Input;
			$clinical->stay_flu14_place_specify = $request->stayFlu14PlaceSpecifyInput;
			$clinical->contact_flu14 = $request->contactFlu14Input;
			$clinical->visit_flu14 = $request->visitFlu14Input;
			$clinical->health_care_worker = $request->healthcareWorkerInput;
			$clinical->suspect_flu = $request->suspectFluInput;
			$clinical->other_risk = $request->otherRiskInput;
			$clinical->other_risk_specify = $request->otherRiskInputSpecify;
			$clinical->result_cli = $request->resultCliInput;
			$clinical->result_cli_refer = $request->resultCliReferInput;
			$clinical->result_cli_other = $request->resultOtherCliInput;
			$clinical->reported_at = parent::convertDateToMySQL($request->reportDateInput);
			//$clinical->ref_user_id = $request->userIdInput;

			/* get specimen ref data from ref_specimen table  */
			$specimen_data = parent::specimen()->keyBy('id');

			/* chk specimen input or not  */
			$chk_specimen_count = 0;
			foreach ($specimen_data as $key=>$val) {
				if ($request->has('specimen'.$val->id)) {
					$chk_specimen_count += 1;
				} else {
					continue;
				}
			}

			/* chk specimen_date_input */
			$chk_specimen_date_input = 0;
			foreach ($specimen_data as $key=>$val) {
				if ($request->has('specimen'.$val->id)) {
					$specimenDateInput = $request->input('specimenDate'.$val->id);
					if (!empty($specimenDateInput) || !is_null($specimenDateInput)) {
						$chk_specimen_date_input += 1;
					} else {
						$chk_specimen_date_input += 0;
					}
				} else {
					continue;
				}
			}

			if ($chk_specimen_count != $chk_specimen_date_input) {
				return Redirect::back()->withErrors('โปรดกรอกข้อมูลตัวอย่างให้ครบถ้วน');
				exit;
			} else {
				foreach ($specimen_data as $key=>$val) {
					if ($request->has('specimen'.$val->id)) {
						$params1['ref_pt_id'] = $request->pid;
						$params1['specimen_type_id'] = $request->specimen.$val->id;

						if ($request->has('specimenOth'.$val->id)) {
							$othStr = 'specimenOth'.$val->id;
							$params2['specimen_other'] = $request->$othStr;
						} else {
							$params2['specimen_other'] = NULL;
						}

						$dateStr = 'specimenDate'.$val->id;
						$specimenDate = $request->$dateStr;
						if (!empty($specimenDate)) {
							$params2['specimen_date'] = parent::convertDateToMySQL($specimenDate);
						} else {
							$params2['specimen_date'] = NULL;
						}
						$params2['ref_user_id'] = $request->userIdInput;
						$specimen_saved = Specimen::updateOrCreate($params1, $params2);
					} else {
						continue;
					}
				}
				/* save method */
				DB::beginTransaction();
				try {
					$patient_saved = $this->storePatient($patient);
					$clinical_saved = $clinical->save();
					DB::commit();
					if ($patient_saved == true && $clinical_saved == true) {
						//$message = collect(['status'=>200, 'msg'=>'บันทึกข้อมูลสำเร็จแล้ว', 'title'=>'Flu Right Site']);
						Log::notice('Added or Updated data successfully - uid:  '.auth()->user()->id.' - pid: '.$request->pid);
						return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จแล้ว');
					} else {
						DB::rollback();
						//$message = collect(['status'=>500, 'msg'=>'Internal Server Error! Something Went Wrong!', 'title'=>'Flu Right Site']);
						Log::error('Added or Updated Error - uid:  '.auth()->user()->id.' - pid: '.$request->pid);
						return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้ โปรดตรวจสอบอีกครั้ง');
					}
				} catch (Exception $e) {
					DB::rollback();
					//$message = collect(['status'=>500, 'msg'=>'Internal Server Error! Something Went Wrong!', 'title'=>'Flu Right Site']);
					Log::error('Error - uid:  '.auth()->user()->id.' - pid: '.$request->pid.' Message:'.$e->getMessage());
					return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้ โปรดตรวจสอบอีกครั้ง');
				}
				//return redirect()->route('list')->with('message', $message);
			}
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
		$patient = Patients::whereId($id)->get();

		/* get patient clinical */
		$clinical = Clinical::whereRef_pt_id($id)->get();

		/* get patient specimen */
		$patient_specimen = Specimen::whereRef_pt_id($id)->get()->keyBy('specimen_type_id');

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
						$tmp['s_specimen_type_id'] = $v['specimen_type_id'];
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
						$tmp['s_specimen_type_id'] = null;
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
		$patient_lab = Lab::whereRef_patient_id($id)->get()->toArray();

		/* *** set data to array *** */
		$ref_user = User::find($patient[0]->ref_user_id)->toArray();

		/* user full name */
		$utn_key = $ref_user['title_name'];
		if ($utn_key == 6) {
			$utn = $ref_user['title_name_other'];
		} else {
			$utn = $titleName[$utn_key]->title_name;
		}

		$uFullName = $utn.$ref_user['name']." ".$ref_user['lastname'];
		$data['user_fullname'] = $uFullName;

		/* user office */
		$user_office = parent::hospitalByCode($ref_user['hospcode']);
		$uOffice = $user_office[0]->hosp_name;
		$data['user_office'] = $uOffice;

		/* user province */
		$uProvince = $provinces[$ref_user['province']]->province_name;
		$data['user_province'] = $uProvince;

		/* user phone/fax */
		$data['user_phone'] = $ref_user['phone'];
		$data['user_fax'] = $ref_user['fax'];

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

		return view('patients.show', [
				'symptoms' => $symptoms,
				'specimen' => $specimen,
				'pathogen' => $pathogen,
				'patient_lab' => $patient_lab,
				'data' => $data
			]
		);
	}

	public function editPatient(Request $request) {
		return 'Comming soon !!';
	}

	public function store(Request $request) {}

	public function storePatient($data) {
		try {
			$pt = new Patients;
			$pt = $data;
			return $pt->save();
		} catch(\Exception $e) {
			return $e->getMessage();
		}
	}

	public function districtFetch(Request $request) {
		$coll = parent::districtByProv($request->id);
		$districts = $coll->keyBy('district_id');
		$htm = "<option value=\"0\">-- โปรดเลือก --</option>";
		foreach ($districts as $key => $val) {
			$htm .= "<option value=\"".$val->district_id."\">".$val->district_name."</option>";
		}
		return $htm;
	}

	public function subDistrictFetch(Request $request) {
		$coll = parent::subDistrictByDistrict($request->id);
		$sub_districts = $coll->keyBy('sub_district_id');
		$htm = "<option value=\"0\">-- โปรดเลือก --</option>";
		foreach ($sub_districts as $key => $val) {
			$htm .= "<option value=\"".$val->sub_district_id."\">".$val->sub_district_name."</option>";
		}
		return $htm;
	}
}
