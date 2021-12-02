<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RapidGraph;
use App\MonthGraph;
use DB;
use App\HelperClass\Helper as CmsHelper;
use App\Provinces;
use App\MonthMedian;
use App\WeekMedian;
use App\SexGroup;
use App\AgeGroup;
use App\NationGroup;
use App\WeekSeparate;

class InitialController extends Controller
{

	public function index() {
		$provinces = Provinces::all()->sortBy('province_name')->keyBy('province_id')->toArray();
		$list_year = CmsHelper::List_year();

		$case_gen_code = DB::table('first_dash')->sum('case_gen_code');
		$case_hos_send = DB::table('first_dash')->sum('case_hos_send');
		$case_lab_confirm = DB::table('first_dash')->sum('case_lab_confirm');
		$case_male = DB::table('first_dash')->sum('case_male');
		$case_female = DB::table('first_dash')->sum('case_female');

		/* month graph */
		$month_graph = MonthGraph::all();
		$month_graph = $month_graph->keyBy('hospital')->toArray();

		/* rapid test data for graph */
		$rapid = RapidGraph::all();
		$rapid = $rapid->keyBy('hospital')->toArray();
		$rapidResult = array('flua'=> 0, 'flub' => 0, 'nagative' => 0, 'unknown' => 0);
		foreach ($rapid as $key => $value) {
			$rapidResult['flua'] += $value['rapid_flua'];
			$rapidResult['flub'] += $value['rapid_flub'];
			$rapidResult['nagative'] += $value['rapid_nagative'];
			$rapidResult['unknown'] += $value['rapid_unknow'];
		}

		$antiResult = array('anti_arv' => 0, 'anti_osel' => 0, 'anti_tamiflu' => 0, 'anti_unknown' => 0);
		foreach ($rapid as $key => $value) {
			$antiResult['anti_arv'] += $value['anti_arv'];
			$antiResult['anti_osel'] += $value['anti_osel'];
			$antiResult['anti_tamiflu'] += $value['anti_tamiflu'];
			$antiResult['anti_unknown'] += $value['anti_unknow'];
		}

		$case_all = $case_gen_code+$case_hos_send+$case_lab_confirm;

		// Percent Male/Female
		$t_male = SexGroup::sum('male');
		$t_female = SexGroup::sum('female');
		$t_total = SexGroup::sum('totals');

		$percent_male = CmsHelper::Cal_percent($t_male,$t_total);
		$percent_female = CmsHelper::Cal_percent($t_female,$t_total);

		$donut_charts_sex_arr = array(
			array("label" => "Male" ,"symbol" => "Male","y" =>$percent_male),
			array("label" => "Female" ,"symbol" => "Female","y" =>$percent_female)
		);

		// Age Group
		$datas_age1 = AgeGroup::sum('under1y');
		$datas_age2 = AgeGroup::sum('1-4y');
		$datas_age['0-4y'] = $datas_age1+$datas_age2;
		$datas_age['5-9y'] = AgeGroup::sum('5-9y');
		$datas_age['10-14y'] = AgeGroup::sum('10-14y');
		$datas_age['15-19y'] = AgeGroup::sum('15-19y');
		$datas_age['20-24y'] = AgeGroup::sum('20-24y');
		$datas_age['25-29y'] = AgeGroup::sum('25-29y');
		$datas_age['30-34y'] = AgeGroup::sum('30-34y');
		$datas_age['35-39y'] = AgeGroup::sum('35-39y');
		$datas_age['40-44y'] = AgeGroup::sum('40-44y');
		$datas_age['45-49y'] = AgeGroup::sum('45-49y');
		$datas_age['50-54y'] = AgeGroup::sum('50-54y');
		$datas_age['55-59y'] = AgeGroup::sum('55-59y');
		$datas_age['60-64y'] = AgeGroup::sum('60-64y');
		$datas_age['65up'] = AgeGroup::sum('65up');
		$datas_age['unknow'] = AgeGroup::sum('unknow');

		$sum_age_group = 0;

		foreach($datas_age as $key_age => $val_age){
			$sum_age_group += $val_age;
			$line_charts_age_group_arr[] = array("label"=> $key_age, "y"=> $val_age);
		}
		//dd($line_charts_age_group_arr);

		// Nation Graph
		$total_nation = NationGroup::sum('totals');
		$datas_nation['Thai'] = NationGroup::sum('thai');
		$datas_nation['Burmese'] = NationGroup::sum('burmese');
		$datas_nation['Lao'] = NationGroup::sum('lao');
		$datas_nation['Cambodian'] = NationGroup::sum('cambodian');
		$datas_nation['Other'] = NationGroup::sum('other');

		$sum_nation_group = 0;

		foreach($datas_nation as $key_nation => $val_nation){
			$sum_nation_group += $val_nation;

			$line_charts_nation_group_arr[] = array("label"=> $key_nation, "y"=> CmsHelper::Cal_percent($val_nation,$total_nation));
		}
		$datas_nation['nation_totals'] = NationGroup::sum('totals');

		/* Median */
		$year_now = date('Y');
		$year_last_med = $year_now-1;
		$year_back_3 = $year_now-3;

		$arr_month = array(
			"01" => "Jan",
			"02" => "Feb",
			"03" => "Mar",
			"04" => "Apr",
			"05" => "May",
			"06" => "Jun",
			"07" => "Jul",
			"08" => "Aug",
			"09" => "Sep",
			"10" => "Oct",
			"11" => "Nov",
			"12" => "Dec"
		);

		for($i=1; $i<=12; $i++){
			$result1 = MonthMedian::selectRaw('year_result,month_result,sum(totals) AS totals')
			->whereBetween('year_result',[$year_back_3,$year_last_med])
			->where('month_result',str_pad($i,2,"0",STR_PAD_LEFT))
			->groupBy('year_result','month_result')
			->orderBy('totals','ASC')
			->limit(1,1)
			->first();
			$data_three_year_median[] = array("label" => $arr_month[str_pad($i,2,"0",STR_PAD_LEFT)],"y" => $result1['totals']);
		}

		for($i=1; $i<=12; $i++){
			$result2 = MonthMedian::selectRaw('year_result,month_result,sum(totals) AS totals')
			->where('year_result',$year_now)
			->where('month_result',$i)
			->groupBy('year_result','month_result')
			->orderBy('totals','ASC')
			->first();

			$arr_now_year_median[] = $result2;
		}

		foreach($arr_now_year_median as $val){
			$pt_data[$val['month_result'] ?? ''] = $val['totals'] ?? '';
		}

		foreach ($arr_month as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$data_now_year_median[$arr_month[$key]] = $pt_data[$key];
			}else{
				$data_now_year_median[$arr_month[$key]] = 0;
			}
		}

		/* Collation Data Now Median Year Graph */
		foreach ($data_now_year_median as $key1 => $val1){
			$result_data_now_year_median[] = array("label" => $key1 , "y" => $val1);
		}

		$arr_week = array(
			"01" => "wk01",
			"02" => "wk02",
			"03" => "wk03",
			"04" => "wk04",
			"05" => "wk05",
			"06" => "wk06",
			"07" => "wk07",
			"08" => "wk08",
			"09" => "wk09",
			"10" => "wk10",
			"11" => "wk11",
			"12" => "wk12",
			"13" => "wk13",
			"14" => "wk14",
			"15" => "wk15",
			"16" => "wk16",
			"17" => "wk17",
			"18" => "wk18",
			"19" => "wk19",
			"20" => "wk20",
			"21" => "wk21",
			"22" => "wk22",
			"23" => "wk23",
			"24" => "wk24",
			"25" => "wk25",
			"26" => "wk26",
			"27" => "wk27",
			"28" => "wk28",
			"29" => "wk29",
			"30" => "wk30",
			"31" => "wk31",
			"32" => "wk32",
			"33" => "wk33",
			"34" => "wk34",
			"35" => "wk35",
			"36" => "wk36",
			"37" => "wk37",
			"38" => "wk38",
			"39" => "wk39",
			"40" => "wk40",
			"41" => "wk41",
			"42" => "wk42",
			"43" => "wk43",
			"44" => "wk44",
			"45" => "wk45",
			"46" => "wk46",
			"47" => "wk47",
			"48" => "wk48",
			"49" => "wk49",
			"50" => "wk50",
			"51" => "wk51",
			"52" => "wk52"
		);

		for($i=1; $i<=52; $i++){
			$result3 = WeekMedian::selectRaw('week_result,year_result,sum(totals) AS totals')
			->whereBetween('year_result',[$year_back_3,$year_last_med])
			->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
			->groupBy('year_result','week_result')
			->orderBy('totals','ASC')
			->limit(1,1)
			->first();

			$data_week_median[] = array("label" => $arr_week[str_pad($i,2,"0",STR_PAD_LEFT)],"y" => $result3['totals']);
		}


		for($i=1; $i<=52; $i++){
			$result4 = WeekMedian::selectRaw('week_result,year_result,sum(totals) AS totals')
			->where('year_result',$year_now)
			->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
			->groupBy('year_result','week_result')
			->orderBy('totals','ASC')
			->first();

			$arr_now_week_median[] = $result4;
		}

		foreach($arr_now_week_median as $val){
			$pt_data[$val['week_result'] ?? ''] = $val['totals'] ?? '';
		}

		foreach ($arr_week as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$data_now_week_median[$arr_week[$key]] = $pt_data[$key];
			}else{
				$data_now_week_median[$arr_week[$key]] = 0;
			}
		}

		/* Collation Data Now Median Week Graph */
		foreach ($data_now_week_median as $key1 => $val1){
			$result_data_now_week_median[] = array("label" => $key1 , "y" => $val1);
		}
		/** Median **/

		// RP_Separate
		for($i=1; $i<=52; $i++){
		$rp_week_length[] = "wk".$i;
		}
		//Total

		//Sum Flu Positive
		for($i=1; $i<=52; $i++){
			$query_flu_positive = WeekSeparate::selectRaw('(sum(Flu_A_H1)+sum(Flu_A_H1pdm09)+sum(Flu_A_H3)+sum(Influenza_B)) * 100 / sum(totals) AS percent')
														->where('year_result',$year_now)
														->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
														->orderBy('percent','ASC')
														->first();
			$sum_flu_positive[] = 	$query_flu_positive;
		}

		foreach($sum_flu_positive as $key => $val2){
			$result_sum_flu_positive_data_now_week[] = $val2->percent;
		}

		// Sum Flu_A_H3
		for($i=1; $i<=52; $i++){

			$query_flu_a_h3 = WeekSeparate::selectRaw('sum(Flu_A_H3) as Flu_A_H3,week_result,year_result')
														->where('year_result',$year_now)
														->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
														->groupBy('week_result','year_result')
														->first();
			$sum_flu_a_h3[] = $query_flu_a_h3;
		}

		foreach($sum_flu_a_h3 as $val2){
			$pt_data[$val2['week_result'] ?? ''] = $val2['Flu_A_H3'] ?? '';
		}

		foreach ($arr_week as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$sum_flu_a_h3_data_now_week[$arr_week[$key]] = $pt_data[$key];
			}else{
				$sum_flu_a_h3_data_now_week[$arr_week[$key]] = 0;
			}
		}

		/* Collation Data Now Flu_A_H3 Week Graph */
		foreach ($sum_flu_a_h3_data_now_week as $key1 => $val1){
			$result_sum_flu_a_h3_data_now_week[] = $val1;
		}

		// Sum Flu_A_H1pdm09
		for($i=1; $i<=52; $i++){

			$query_flu_a_h12009 = WeekSeparate::selectRaw('sum(Flu_A_H1pdm09)+sum(Flu_A_H1) as h1_totals,week_result,year_result')
														->where('year_result',$year_now)
														->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
														->groupBy('week_result','year_result')
														->first();
			$sum_flu_a_h12009[] = $query_flu_a_h12009;
		}

		foreach($sum_flu_a_h12009 as $val2){
			$pt_data[$val2['week_result'] ?? ''] = $val2['h1_totals'] ?? '';
		}

		foreach ($arr_week as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$sum_flu_a_h12009_data_now_week[$arr_week[$key]] = $pt_data[$key];
			}else{
				$sum_flu_a_h12009_data_now_week[$arr_week[$key]] = 0;
			}
		}

		/* Collation Data Now flu_a_h12009 Week Graph */
		foreach ($sum_flu_a_h12009_data_now_week as $key1 => $val1){
			$result_sum_flu_a_data_now_week[] = $val1;
		}

		// Sum Flu B
		for($i=1; $i<=52; $i++){
			$query_flu_b = WeekSeparate::selectRaw('sum(Influenza_B) as Influenza_B,week_result,year_result')
														->where('year_result',$year_now)
														->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
														->groupBy('week_result','year_result')
														->first();
			$sum_flu_b[] = $query_flu_b;
		}

		foreach($sum_flu_b as $val2){
			$pt_data[$val2['week_result'] ?? ''] = $val2['Influenza_B'] ?? '';
		}

		foreach ($arr_week as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$sum_flu_b_data_now_week[$arr_week[$key]] = $pt_data[$key];
			}else{
				$sum_flu_b_data_now_week[$arr_week[$key]] = 0;
			}
		}
		/* Collation Data Now flu_b Week Graph */
		foreach ($sum_flu_b_data_now_week as $key1 => $val1){
			$result_sum_flu_b_data_now_week[] = $val1;
		}

		// Sum Negative
		for($i=1; $i<=52; $i++){

			$query_negative = WeekSeparate::selectRaw('sum(Negative) as Negative,week_result,year_result')
														->where('year_result',$year_now)
														->where("week_result" ,"=" ,str_pad($i,2,"0",STR_PAD_LEFT))
														->groupBy('year_result','week_result')
														->first();
			$sum_negative[] = $query_negative;
		}

		foreach($sum_negative as $val2){
			$pt_data[$val2['week_result'] ?? ''] = $val2['Negative'] ?? '';
		}

		foreach ($arr_week as $key => $value) {
			if (array_key_exists($key, $pt_data)) {
				$sum_negative_data_now_week[$arr_week[$key]] = $pt_data[$key];
			}else{
				$sum_negative_data_now_week[$arr_week[$key]] = 0;
			}
		}

		/* Collation Data Now negative  Week Graph */
		foreach ($sum_negative_data_now_week as $key1 => $val1){
			$result_sum_negative_data_now_week[] = $val1;
		}

		return view('initial.index',
				compact(
					'case_gen_code',
					'case_hos_send',
					'case_lab_confirm',
					'case_all',
					'donut_charts_sex_arr',
					'line_charts_age_group_arr',
					'line_charts_nation_group_arr',
					'rapidResult',
					'antiResult',
					'provinces',
					'list_year',
					'data_three_year_median',
					'result_data_now_year_median',
					'data_week_median',
					'result_data_now_week_median',
					'rp_week_length',
					'result_sum_flu_b_data_now_week',
					'result_sum_negative_data_now_week',
					'result_sum_flu_a_h3_data_now_week',
					'result_sum_flu_a_data_now_week',
					'result_sum_flu_positive_data_now_week'
				)
		);
	}

	public function create() {
		//
	}

	public function store(Request $request) {
		//
	}

	public function show($id) {
		//
	}

	public function edit($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		//
	}
}
