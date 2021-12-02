<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Session;
use App\Counter;
use DB;

class HomeController extends BoeFrsController
{
	public function __construct(){
		$this->middleware('auth');
		$this->middleware(['role:admin|hospital|lab|dmsc|hosp-group']);
		$this->middleware('page_session');

		$this->yesterday = date('Y-m-d', strtotime("-1 day"));
		$this->today = date('Y-m-d');
	}

	public function index(Request $request) {
		/* set counter */
		$last_created = $this->getIpAddrPeriodTime($_SERVER['REMOTE_ADDR']);
		if (count($last_created) <= 0) {
			$this->addTodayToDb();
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
		}

		/* check permission and redirect */
		$roleArr = auth()->user()->roles->pluck('name');
		switch ($roleArr[0]) {
			case 'admin':
				return redirect()->route('dashboard.index');
				break;
			case 'hospital':
			case 'lab':
			case 'dmsc':
			case 'hosp-group':
				return redirect()->route('dashboard.index');
				break;
			default:
				return redirect()->route('logout');
				break;
		}
		// $userRole = $roleArr[0];
		// if ($userRole == 'admin') {
		// 	return redirect()->route('dashboard.index');
		// } elseif ($userRole == 'hospital' || $userRole == 'lab' || $userRole == 'dmsc') {
		// 	return redirect()->route('dashboard.index');
		// } else {
		// 	return redirect()->route('logout');
		// }
	}

	private function addTodayToDb() {
		$cnt = new Counter;
		$cnt->cnt_date = date('Y-m-d');
		$cnt->cnt_ip = $_SERVER['REMOTE_ADDR'];
		$save = $cnt->save();
		return $save;
	}

	private function getIpAddrPeriodTime($ip_addr) {
		return Counter::select('created_at')
			->where('cnt_ip', '=', $ip_addr)
			->orderBy('created_at', 'DESC')
			->limit(1)
			->get()
			->toArray();
	}

	private function checkRepeatDate() {
		$result = DB::table('daily')->select('daily_date')->where('daily_date', '=', $this->yesterday)->get()->toArray();
		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function getYesterdayData() {
		return Counter::select(DB::raw('COUNT(*) AS intYesterday'))
			->where('cnt_date', '=', $this->yesterday)
			->get()
			->toArray();
	}

	private function addYesterdayDataToTaDb($cnt=0) {
		return DB::table('daily')->insert(
			['daily_date' => $this->yesterday, 'daily_num' => $cnt]
		);
	}

	private function deleteYesterdayData() {
		return Counter::where('cnt_date', '!=', $this->today)->delete();
	}
}
