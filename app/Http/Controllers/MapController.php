<?php

namespace App\Http\Controllers;

use App\Map;
use App\Hospitals;
use App\Provinces;
use Illuminate\Http\Request;
use DB;

class MapController extends Controller
{
	public function index() {
		$provinces = self::getProvinces();
		$rp_map = DB::table('z_rp_map')
			->leftJoin('ref_province', 'z_rp_map.hos_prov', '=', 'ref_province.province_id')
			->select('ref_province.province_id', 'z_rp_map.year_result', 'z_rp_map.lab_code', 'z_rp_map.lat', 'z_rp_map.lon')
			->whereNotNull('z_rp_map.lab_code')
			->orderBy('z_rp_map.lab_code', 'ASC')
			->get();
		$marker_data = DB::table('z_rp_map')->select(
			'hos_prov',
			'lon',
			'lat',
			\DB::raw('SUM(IF(lab_code = "2", 1, 0)) AS "b"'),
			\DB::raw('SUM(IF(lab_code = "5", 1, 0)) AS "flu_a_h1"'),
			\DB::raw('SUM(IF(lab_code = "6", 1, 0)) AS "flu_a_h1p"'),
			\DB::raw('SUM(IF(lab_code = "7", 1, 0)) AS "flu_a_h3"'),
			\DB::raw('SUM(IF(lab_code = "86", 1, 0)) AS "neg"'),
			\DB::raw('SUM(IF(lab_code = "97", 1, 0)) AS "bad_exam"'),
			\DB::raw('SUM(IF(lab_code = "99", 1, 0)) AS "other"'))->groupBy('hos_prov', 'lon', 'lat')->get()->keyBy('hos_prov');

		$marker_coll = collect();
		foreach ($marker_data as $key => $value) {
			$pc_b = (($value->b/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_flu_a_h1 = (($value->flu_a_h1/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_flu_a_h1p = (($value->flu_a_h1p/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_flu_a_h3 = (($value->flu_a_h3/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_neg = (($value->neg/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_bad_exam = (($value->bad_exam/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);
			$pc_other = (($value->other/($value->b+$value->flu_a_h1+$value->flu_a_h1p+$value->flu_a_h3+$value->neg+$value->bad_exam+$value->other))*100);

			$tmp['pc_b'] = $pc_b;
			$tmp['b'] = $value->b;

			$tmp['pc_flu_a_h1'] = $pc_flu_a_h1;
			$tmp['flu_a_h1'] = $value->flu_a_h1;

			$tmp['pc_flu_a_h1p'] = $pc_flu_a_h1p;
			$tmp['flu_a_h1p'] = $value->flu_a_h1p;

			$tmp['pc_flu_a_h3'] = $pc_flu_a_h3;
			$tmp['flu_a_h3'] = $value->flu_a_h3;

			$tmp['pc_neg'] = $pc_neg;
			$tmp['neg'] = $value->neg;

			$tmp['pc_bad_exam'] = $pc_bad_exam;
			$tmp['bad_exam'] = $value->bad_exam;

			$tmp['pc_other'] = $pc_other;
			$tmp['other'] = $value->other;

			$marker_coll[$key] = $tmp;
		}

		return view('maps.spread', [
				'provinces' => $provinces,
				'rp_map' => $rp_map,
				'marker_coll' => $marker_coll
			]
		);
	}

	public function pin() {
		$hospName = self::getFrsHospName();
		if (!is_null($hospName)) {
			$marker_map = DB::table('z_rp_map_marker')->select(
				'hoscode',
				'lon',
				'lat',
				\DB::raw('SUM(IF(lab_code = "2", 1, 0)) AS "b"'),
				\DB::raw('SUM(IF(lab_code = "5", 1, 0)) + SUM(IF(lab_code = "6", 1, 0)) AS "flu_a"'),
				\DB::raw('SUM(IF(lab_code = "7", 1, 0)) AS "flu_h"'),
				\DB::raw('SUM(IF(lab_code = "86", 1, 0)) AS "neg"')
			)->groupBy('hoscode', 'lon', 'lat')->get()->keyBy('hoscode');
		} else {
			$marker_map = null;
		}
		return view('maps.pin', [
			'hosp_name' => $hospName,
			'marker_map' => $marker_map
		]);
	}

	public function marker() {
		$hospName = self::getFrsHospName();
		if (!is_null($hospName)) {
			$marker_map = DB::table('z_rp_map_marker')->select(
				'hoscode',
				'lon',
				'lat',
				\DB::raw('SUM(IF(lab_code = "2", 1, 0)) AS "b"'),
				\DB::raw('SUM(IF(lab_code = "5", 1, 0)) + SUM(IF(lab_code = "6", 1, 0)) AS "flu_a"'),
				\DB::raw('SUM(IF(lab_code = "7", 1, 0)) AS "flu_h"'),
				\DB::raw('SUM(IF(lab_code = "86", 1, 0)) AS "neg"')
			)->groupBy('hoscode', 'lon', 'lat')->get()->keyBy('hoscode');

		} else {
			$marker_map = null;
		}
		return view('maps.marker', [
			'hosp_name' => $hospName,
			'marker_map' => $marker_map
		]);
	}

	public function chart() {
		$hospName = self::getFrsHospName();
		if (!is_null($hospName)) {
			$marker_map = DB::table('z_rp_map_marker')->select(
				'hoscode',
				'lon',
				'lat',
				\DB::raw('SUM(IF(lab_code = "2", 1, 0)) AS "b"'),
				\DB::raw('SUM(IF(lab_code = "5", 1, 0)) + SUM(IF(lab_code = "6", 1, 0)) AS "flu_a"'),
				\DB::raw('SUM(IF(lab_code = "7", 1, 0)) AS "flu_h"'),
				\DB::raw('SUM(IF(lab_code = "86", 1, 0)) AS "neg"')
			)->groupBy('hoscode', 'lon', 'lat')->get()->keyBy('hoscode');

		} else {
			$marker_map = null;
		}
		return view('maps.chart', [
			'hosp_name' => $hospName,
			'marker_map' => $marker_map
		]);
	}

	private function getFrsHospcode() {
		return DB::table('z_rp_map_marker')->select('hoscode')->groupBy('hoscode')->get()->toArray();
	}

	private function getFrsHospName() {
		$frsHospcode = self::getFrsHospcode();
		if (count($frsHospcode) > 0) {
			foreach ($frsHospcode as $value) {
				$result = Hospitals::select('hosp_name')->where('hospcode', '=', $value->hoscode)->get()->toArray();
				$hospName[$value->hoscode] = $result[0]['hosp_name'];
			}
			return $hospName;
		} else {
			return null;
		}
	}

	private function getProvinces() {
		return Provinces::select('province_id', 'province_name')->get()->keyBy('province_id')->toArray();
	}
}
