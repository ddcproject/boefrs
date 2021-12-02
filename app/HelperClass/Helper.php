<?php
namespace App\HelperClass;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;

    class Helper{
        function __construct()
        {
            //echo 'test';
        }


        public static function DateThai($strDate){
          if($strDate=='0000-00-00' || $strDate=='' || $strDate==null) return '-';
              $strYear = date("Y",strtotime($strDate))+543;
              $strMonth= date("n",strtotime($strDate));
              $strDay= date("j",strtotime($strDate));
              $strHour= date("H",strtotime($strDate));
              $strMinute= date("i",strtotime($strDate));
              $strSeconds= date("s",strtotime($strDate));
              $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
              $strMonthThai=$strMonthCut[$strMonth];
          return "$strDay $strMonthThai $strYear";
        }

        public static function MonthThai($strDate){
          if($strDate=='0000-00-00' || $strDate=='' || $strDate==null) return '-';
              $strYear = date("Y",strtotime($strDate))+543;
              $strMonth= date("n",strtotime($strDate));
              $strDay= date("j",strtotime($strDate));
              $strHour= date("H",strtotime($strDate));
              $strMinute= date("i",strtotime($strDate));
              $strSeconds= date("s",strtotime($strDate));
              $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
              $strMonthThai=$strMonthCut[$strMonth];
          return "$strMonthThai $strYear";
        }

        public static function Date_Format_BC_To_AD($strDate){
          if(empty($strDate)) return false;
            $bc_year = explode("/",$strDate);
            $day = $bc_year['0'];
            $month = $bc_year['1'];
            $year = $bc_year['2']-543;
          return $year.'-'.$month.'-'.$day;
        }

        public static function Date_Format_ฺAD_To_BC($strDate){
          if(empty($strDate)) return false;
          $ad_year = explode("-",$strDate);
          $day = $ad_year['2'];
          $month = $ad_year['1'];
          $year = $ad_year['0']+543;
          return $day.'/'.$month.'/'.$year;
        }
        public static function Date_Format_Custom($strDate){
          if(empty($strDate)) return false;
            $bc_year = explode("-",($strDate));
            $day = $bc_year['2'];
            $month = $bc_year['1'];
            $year = $bc_year['0'];
          return $year.'-'.$month.'-'.$day;
        }


        public static function Cal_percent($sum_val,$sum_total){
          //if(empty($sum_val) || empty($sum_total)) return NULL;
          $get_sum_val = (isset($sum_val)) ? $sum_val : 0;
          $get_sum_total = (isset($sum_total)) ? $sum_total : 0;
          $percent = ($get_sum_val*100)/$get_sum_total;
          return number_format($percent,2);
        }

        public static function Cal_ratio($sum_val,$sum_total){
          //if(empty($sum_val) || !isset($sum_total)) return NULL;
          $sum_val = (isset($sum_val)) ? $sum_val : 0;
          $sum_total = (isset($sum_total)) ? $sum_total : 0;
          $percent = ($sum_val/$sum_total)*100;
          return number_format($percent);
        }

        public static function List_year(){

          $value = Cache::remember('list_year', 60, function()
          {
              return DB::table('z_rp_sex')->select('year_result')->groupBy('year_result')->get()->toArray();
          });

          return $value;
        }



}
