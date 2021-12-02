<?php
use App\HelperClass\Helper as CmsHelper;
$current_date = CmsHelper::DateThai(date('Y-m-d'));
$th_year = date('y')+43;


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>LABResult-{{ $data->analyze_id }}</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
<style>

body{
 font-family: 'Sarabun', sans-serif;
 font-size: 16px;
}
@page {
      size: A4;
      padding: 15px;
    }
    @media print {
      html, body {
        width: 210mm;
        height: 297mm;
        /*font-size : 16px;*/
      }
    }
.tblresult {
  border: 1px solid black;
}
 table { border-collapse: collapse; }
</style>
</head>
<body>

<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 100%; ">
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 253px;" valig="top"><img src="https://apps.doe.moph.go.th/boefrs/assets/images/dmslogo.png" width="220px" height="140px"></td>
<td style="width: 272px;">&nbsp;</td>
<td style="width: 149px;" align="right">&nbsp;ลำดับที่ <?php echo $th_year."-".str_pad($data->lab_id, 5, '0', STR_PAD_LEFT); ?></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 100%;" align="center">รายงานผลการตรวจวิเคราะห์<br />สถาบันวิจัยวิทยาศาสตร์สาธารณสุข กรมวิทยาศาสตร์การแพทย์ กระทรวงสาธารณสุข<br />ถนนติวานนท์ อำเภอเมือง จังหวัดนนทบุรี 11000&nbsp;</td>
</tr>
<tr>
<td style="width: 100%;" align="center">&nbsp;</td>
</tr>
<tr>
<td style="width: 100%;">
  <hr size=3 noshadow>
<table style="width: 100%;">
<tbody>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">เลขที่ใบกำกับ: {{ str_pad($data->lab_id, 5, '0', STR_PAD_LEFT) }}</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">วันที่รับตัวอย่าง: {{ CmsHelper::DateThai($data->receive_date) }}</td>
<td style="width: 414px; height: 23px;">วันที่รายงานผล: {{ CmsHelper::DateThai($data->lab_result_date) }}</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">ผู้ส่งตรวจ: @if($data->hosp_name){{ $data->hosp_name }}@else-@endif</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">วัตถุประสงค์: ตรวจหาสารพันธุกรรมไวรัสไข้หวัดใหญ่</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">ชื่อรายการทดสอบ: การตรวจหาสารพันธุกรรมไวรัสไข้หวัดใหญ่ ด้วยเทคนิค Real time RT-PCR</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">วิธีการทดสอบ: Real time RT-PCR</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
<tr style="height: 23px;">
<td style="width: 262px; height: 23px;">ผลการวิเคราะห์:</td>
<td style="width: 414px; height: 23px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 100%;">
<table style="width: 100%;" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC">
<tbody>
<tr >
<td style="width: 149px; ">&nbsp;หมายเลขวิเคราะห์</td>
<td style="width: 327px;" align="center">&nbsp;รายละเอียดสิ่งส่งตรวจ</td>
<td style="width: 200px;" align="center">ผลการตรวจวิเคราะห์</td>
</tr>
<tr>
<td style="width: 149px;">{{ $data->analyze_id }}</td>
<td style="width: 327px;">@if($data->title_name!="อื่นๆ ระบุ"){{ $data->title_name }}@else {{ $data->title_name_other }}@endif {{ $data->patients_first_name }} {{ $data->patients_last_name }}<br />อายุ {{ $data->patients_age_year }} ปี<br />ชนิดตัวอย่าง @if($data->specimen_type_name!="Other"){{ $data->specimen_type_name }}@else{{ $data->specimen_type_name }} ,{{ $data->specimen_other }}@endif</td>
<td style="width: 200px;">{{ $data->result_patho_name_th }}</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 100%;">
<table style="width: 100%">
<tbody>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
<td style="width: 50%;" align="center">......................................................... ผู้วิเคราะห์<br />(นายภากร ภิรมย์ทอง)<br />นักวิทยาศาสตร์การแพทย์<br /> {{ $current_date }}</td>
<td style="width: 50%;" align="center">......................................................... ผู้รับรองรายงานผล<br />(นางสาวสิริภาภรณ์ ผุยกัน)<br />นักวิทยาศาสตร์การแพทย์ชำนาญการ<br /> {{ $current_date }}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- DivTable.com -->
</body>
</html>
