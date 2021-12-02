<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SampleSubmissionsController extends Controller
{
	public function Form_Sample_Submissions(){
		$symptoms = DB::connection('mysql')->table('ref_symptoms')->get();
		$ref_title_name = DB::connection('mysql')->table('ref_title_name')->get();
		$ref_specimen = DB::connection('mysql')->table('ref_specimen')->whereNotIn('id_specimen',[9])->get();
		return view('admin.sample-submissions.form-sample-submissions')->with(compact('symptoms','ref_title_name','ref_specimen'));
	}

}
