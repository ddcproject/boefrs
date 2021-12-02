<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;
use App\Symptoms;
use App\Patients;
use App\Hospitals;

class BoeFrsController extends Controller implements BoeFrs
{
	public $result;
	public $title_name;
	public $hospLst;

	public function __construct() {
		$titleName = $this->titleName();
		$this->title_name = $titleName->keyBy('id');
		$this->result = null;
		$this->hospLst = null;
	}

	public function titleName() {
		return DB::connection('mysql')->table('ref_title_name')->get();
	}

	public function symptoms() {
		return Symptoms::all();
		//return DB::connection('mysql')->table('ref_symptoms')->get();
	}

	public function specimen() {
		return DB::connection('mysql')->table('ref_specimen_type')->get();
	}

	public function patients() {
		return DB::connection('mysql')->table('patients')->get();
	}

	public function patientsById($id=0) {
		return DB::connection('mysql')
			->table('patients')
			->where([['id', '=', $id]])
			->whereNull('deleted_at')
			->orderBy('id', 'desc')
			->get();
	}

	public function patientByField($field=null, $value=null) {
		return DB::connection('mysql')
			->table('patients')
			->where($field, '=', $value)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientByAdmin($hosp_status='new') {
		return DB::connection('mysql')
			->table('patients')
			->where('hosp_status', '=', $hosp_status)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientAllByAdmin() {
		$patients = Patients::whereNull('deleted_at')->get();
		return $patients;
	}

	protected function listPatientByAdmin($lab_status=array()) {
		return DB::connection('mysql')
			->table('patients')
			->where('lab_status', '=', $lab_status)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientByUser($user=null, $lab_status='new') {
		return DB::connection('mysql')
			->table('patients')
			->where('ref_user_id', '=', $user)
			->where('lab_status', '=', $lab_status)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientByUserHospcode($hospcode=null, $hosp_status='new') {
		return DB::connection('mysql')
			->table('patients')
			->where('ref_user_hospcode', '=', $hospcode)
			->where('hosp_status', '=', $hosp_status)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientAllByUserHospcode($hospcode=null) {
		return Patients::where('ref_user_hospcode', '=', $hospcode)
			->whereNull('deleted_at')
			->get();
	}

	protected function patientByUserHospGroup($hospGroup=array(0), $hosp_status='new'): object {
		return Patients::whereIn('ref_user_hospcode', $hospGroup)->whereHosp_status($hosp_status)->get();
	}

	protected function listHospByGroup($hospGroup=array()): array {
		return Hospitals::select('hospcode', 'hosp_name')->whereIn('hospcode', $hospGroup)->get()->toArray();
	}

	protected function getProvCodeByHospCode($hospcode='0'): array {
		return Hospitals::select('prov_code')->whereHospcode($hospcode)->get()->toArray();
	}

	public function provinces() {
		return DB::connection('mysql')
			->table('ref_province')
			->orderBy('province_name', 'asc')
			->get();
	}

	public function provinceListArr() {
		$prov = DB::connection('mysql')
				->table('ref_province')
				->orderBy('province_name', 'asc')
				->get();
		$provinces = $prov->keyBy('province_id');
		$provinces->all();
		return $provinces;
	}

	public static function provinceList() {
		$prov = DB::connection('mysql')
				->table('ref_province')
				->orderBy('province_name', 'asc')
				->get();
		$provinces = $prov->keyBy('province_id');
		$provinces->all();
		return $provinces;
	}

	public function district() {
		return DB::connection('mysql')
			->table('ref_district')
			->orderBy('district_id', 'asc')
			->get();
	}

	public function districtByProv($prov_code=0) {
		return DB::connection('mysql')
			->table('ref_district')
			->where('province_id', '=', $prov_code)
			->orderBy('district_id', 'asc')
			->get();
	}

	public function districtById($ds_id=0) {
		return DB::connection('mysql')
			->table('ref_district')
			->where('district_id', '=', $ds_id)
			->orderBy('district_id', 'asc')
			->get();
	}

	public function subDistrictByDistrict($dist_code=0) {
		return DB::connection('mysql')
			->table('ref_sub_district')
			->where('district_id', '=', $dist_code)
			->orderBy('sub_district_id', 'asc')
			->get();
	}

	public function suBdistrictById($sub_district_id=0) {
		return DB::connection('mysql')
			->table('ref_sub_district')
			->where('sub_district_id', '=', $sub_district_id)
			->orderBy('sub_district_id', 'asc')
			->get();
	}

	public function hospitals(): object {
		return Hospitals::orderBy('id', 'asc')->limit(100)->get();
	}

	public function hospitalByActive(): object {
		return Hospitals::whereBoefrs_active(1)
			->orderBy('id', 'asc')
			->limit(100)
			->get();
	}

	public function hospitalByProv($prov_code=0): object {
		return Hospitals::whereProv_code($prov_code)
			->whereIn('hosp_type_code', ['05', '06', '07', '10', '11'])
			->orderBy('id', 'asc')
			->get();
	}

	public function hospitalByCode($hosp_code=0): object {
		return Hospitals::whereHospcode($hosp_code)->limit(1)->get();
	}

	public function hospitalByBoeFrsActive(): object {
		return Hospitals::whereBoefrs_active('1')->orderBy('hosp_name', 'asc')->get();
	}

	public function nationality() {
		return DB::connection('mysql')
			->table('ref_nationality')
			->orderBy('id', 'asc')
			->get();
	}

	public function occupation() {
		return DB::connection('mysql')
			->table('ref_occupation')
			->orderBy('id', 'asc')
			->get();
	}

	public function pathogen() {
		return DB::connection('mysql')
			->table('ref_pathogen')
			->orderBy('id', 'asc')
			->get();
	}


	/* random for generate the pin */
	public function randPin($prefix=null, $separator=null) {
		// Available alpha caracters
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		// generate a pin based on 2 * x digits + a random character
		$pin = mt_rand(1000, 9999).mt_rand(1000, 9999).$characters[rand(0, strlen($characters) - 1)];
		// get date
		$date = date('Ymd');
		// shuffle the result
		$string = $prefix.$separator.str_shuffle($pin);
		return $string;
	}

	protected function convertDateToMySQL($date='00/00/0000') {
		if (!is_null($date) || !empty($date)) {
			$ep = explode("/", $date);
			$string = $ep[2]."-".$ep[1]."-".$ep[0];
		} else {
			$string = NULL;
		}
		return $string;
	}
	protected function convertMySQLDateFormat($date='0000-00-00', $seperator="/") {
		if (!is_null($date) || !empty($date)) {
			$ep = explode("-", $date);
			$string = $ep[2].$seperator.$ep[1].$seperator.$ep[0];
		} else {
			$string = NULL;
		}
		return $string;
	}

	protected function convertMySQLDateTimeFormat($dateTime='0000-00-00 00:00:00', $seperator="/") {
		if (!is_null($dateTime) || !empty($dateTime)) {
			$ep_date_time = explode(" ", $dateTime);
			$ep_date = explode("-", $ep_date_time[0]);
			$string = $ep_date[2].$seperator.$ep_date[1].$seperator.$ep_date[0]." ".$ep_date_time[1];
		} else {
			$string = NULL;
		}
		return $string;
	}

	protected function arrToStr($arr=array()) {
		if (count($arr) > 0) {
			$str = null;
			for ($i=0; $i < count($arr); $i++) {
				if ($str != null) {
					$str = $str.",";
				}
				$str = $str.$arr[$i];
			}
		}
		return $str;
	}

	protected function refData() {
		$ref = collect([
			'gender' => [
				'male' => 'ชาย',
				'female' => 'หญิง'
			]
		]);
		return $ref;
	}

	protected function getRefData($key=null) {
		$ref = self::refData();
		if (!is_null($key)) {
			$data = $ref->get($key);
			return $data;
		} else {
			return $ref;
		}
	}
}
