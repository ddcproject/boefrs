<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\BoeFrsController;
use Session;

class PageSession extends BoeFrsController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
	{
		/* set thai province to session */
		if (!Session::has('provinces')) {
			$provinces = parent::provinceList();
			Session::put('provinces', $provinces);
		}

		/* set role name to session */
		if (!Session::has('user_role_name')) {
			$roleArr = auth()->user()->roles->pluck('name');
			$userRole = $roleArr[0];
			Session::put('user_role_name', $userRole);
		}

		/* set user hospital to session */
		if (!Session::has('user_hospital_name')) {
			$user_hosp = parent::hospitalByCode(auth()->user()->hospcode);
			$user_hosp = $user_hosp->pluck('hosp_name')->all();
			$user_hosp_name = $user_hosp[0];
			Session::put('user_hospital_name', $user_hosp_name);
		}
		return $next($request);
	}
}
