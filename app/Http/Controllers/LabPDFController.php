<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;
use App\Lab;
class LabPDFController extends Controller
{
    //
    public function LabresultPDF(Request $request){

      if(empty($request->id)) return abort(404);

      $data = Lab::select('lab.id as lab_id','lab.receive_date as receive_date','hospitals.hosp_name as hosp_name',
                'lab.result_date as lab_result_date','lab.analyze_id as analyze_id','ref_title_name.title_name as title_name',
                'patients.title_name_other as title_name_other','patients.first_name as patients_first_name',
                'patients.last_name as patients_last_name','patients.age_year as patients_age_year',
                'specimen.specimen_type_id as specimen_type_id','specimen.specimen_other as specimen_other',
                'ref_pathogen.patho_name_th as result_patho_name_th','ref_specimen_type.name_en as specimen_type_name',
                'lab.ref_patient_id'
               )
              ->where('lab.ref_patient_id',$request->id)
              ->leftjoin('patients', 'patients.id', '=', 'lab.ref_patient_id')
              ->leftjoin('hospitals', 'hospitals.id','=','lab.hospital')
              ->leftjoin('ref_title_name', 'ref_title_name.id', '=','patients.title_name')
              ->leftjoin('ref_specimen_type', 'ref_specimen_type.id', '=','lab.ref_specimen_id')
              ->join('specimen', 'specimen.id', '=', 'lab.ref_specimen_id')
              ->join('ref_pathogen', 'ref_pathogen.id', '=', 'lab.ref_pathogen_id')
              ->first();
      //dd($data);
      if($data){
        $pdf = PDF::loadView('printpdf.labresult_pdf',compact('data'));
        return $pdf->stream($data->analyze_id.'.pdf'); //แบบนี้จะ stream มา preview
        //return $pdf->download('test.pdf'); //แบบนี้จะดาวโหลดเลย
      }else{
        return abort(404);
      }
    }
}
