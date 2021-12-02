<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Counter;
use DB;

class CounterController extends Controller
{
	protected $yesterday;
	protected $today;

	public function __construct() {
		$this->yesterday = date('Y-m-d', strtotime("-1 day"));
		$this->today = date('Y-m-d');
	}

	public function index() {
		$last_created = $this->getIpAddrPeriodTime($_SERVER['REMOTE_ADDR']);
		if (count($last_created) <= 0) {
			$this->addTodayToDb();
			$counter = array(
				'cntToday' => 0,
				'cntYesterday' => 0,
				'cntThisMonth' => 0,
				'cntThisYear' => 0
			);
		} else {
			$last_create_date = strtotime($last_created[0]['created_at']);
			$expire_date = (int)$last_create_date+(60*5);
			$currentDate = strtotime(date('Y-m-d H:i:s'));

			if ($expire_date < $currentDate) {
				if (!$this->checkRepeatDate()) {
					$data = $this->getYesterdayData();
					$this->addYesterdayDataToTaDb($data[0]['intYesterday']);
					$this->deleteYesterdayData();
				}
				$this->addTodayToDb();
			}
			$cnt_today = $this->getTodayCnt();
			$cnt_yesterday = $this->getYesterdayCnt();
			$cnt_this_month = $this->getThisMonthCnt();
			$cnt_this_year = $this->getThisYearCnt();

			$counter = array(
				'cntToday' => $cnt_today[0]['cntToday'],
				'cntYesterday' => $cnt_yesterday[0]->daily_num,
				'cntThisMonth' => $cnt_this_month[0]->cntMonth,
				'cntThisYear' => $cnt_this_year[0]->cntYear
			);
		}
		$monthChart = $this->getMonthChart();
		return view('statistics.counter', [
			'counter' => $counter,
			'monthChart' => $monthChart
		]);
	}

	protected function getYesterdayData() {
		return Counter::select(DB::raw('COUNT(*) AS intYesterday'))
			->where('cnt_date', '=', $this->yesterday)
			->get()
			->toArray();
	}

	protected function addYesterdayDataToTaDb($cnt=0) {
		return DB::table('daily')->insert(
			['daily_date' => $this->yesterday, 'daily_num' => $cnt]
		);
	}

	protected function deleteYesterdayData() {
		return Counter::where('cnt_date', '!=', $this->today)->delete();
	}

	protected function checkRepeatDate() {
		$result = DB::table('daily')->select('daily_date')->where('daily_date', '=', $this->yesterday)->get()->toArray();
		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	protected function addTodayToDb() {
		$cnt = new Counter;
		$cnt->cnt_date = $this->today;
		$cnt->cnt_ip = $_SERVER['REMOTE_ADDR'];
		$save = $cnt->save();
		return $save;
	}

	protected function getIpAddrPeriodTime($ip_addr) {
		return Counter::select('created_at')
			->where('cnt_ip', '=', $ip_addr)
			->orderBy('created_at', 'DESC')
			->limit(1)
			->get()
			->toArray();
	}

	protected function getTodayCnt() {
		return Counter::select(DB::raw('COUNT(cnt_date) AS cntToday'))->where('cnt_date', '=', $this->today)->get()->toArray();
	}

	protected function getYesterdayCnt() {
		return DB::table('daily')->select('daily_num')->where('daily_date', '=', $this->yesterday)->get()->toArray();
	}

	protected function getThisMonthCnt() {
		$this_month = date('Y-m');
		return DB::table('daily')->select(DB::raw('SUM(daily_num) AS cntMonth'))->where(DB::raw('DATE_FORMAT(daily_date, "%Y-%m")'), '=', $this_month)->get()->toArray();
	}

	protected function getThisYearCnt() {
		$this_year = date('Y');
		return DB::table('daily')->select(DB::raw('SUM(daily_num) AS cntYear'))->where(DB::raw('DATE_FORMAT(daily_date, "%Y")'), '=', $this_year)->get()->toArray();
	}

	protected function getMonthChart($year=null) {
		if (is_null($year)) {
			$year = date('Y');
		}
		for ($i=1; $i<=12; $i++) {
			$x = str_pad($i, 2, "0", STR_PAD_LEFT);
			$cond = $year.'-'.$x;
			$rs = DB::table('daily')->select(DB::raw('SUM(daily_num) AS sumMonth'))->where(DB::raw('DATE_FORMAT(daily_date, "%Y-%m")'), '=', $cond)->groupBy(DB::raw('DATE_FORMAT(daily_date, "%Y-%m")'))->get()->toArray();
			if (count($rs) <= 0) {
				$monthChart[$i] = 0;
			} else {
				$monthChart[$i] = (int)$rs[0]->sumMonth;
			}
		}
		return $monthChart;
	}

}
