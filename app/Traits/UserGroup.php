<?php
namespace App\Traits;
use App\UserBundleHosp;

trait UserGroup {
	public function getUserHospcodeToArr($user=0): array {
		$hospGroup = UserBundleHosp::select('hosp_bundle')->whereUser_id(auth()->user()->id)->get();
		$hospGroupArr = explode(',', $hospGroup[0]->hosp_bundle);
		return $hospGroupArr;
	}
}
