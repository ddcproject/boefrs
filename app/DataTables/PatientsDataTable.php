<?php
namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use App\Patients;
use App\User;
use App\UserBundleHosp;
use App\Hospitals;

class PatientsDataTable extends DataTable
{
	public function dataTable($query) {
		$hospitals = array();
		Hospitals::select('hospcode', 'hosp_name')->whereBoefrs_active(1)->get()->each(function($value, $key) use (&$hospitals) {
			$hospitals[$value->hospcode] = $value->hosp_name;
		});
		return datatables()
		->eloquent($query)
		->editColumn('lab_code', function($code) {
			return '<span class="font-1">'.$code->lab_code.'</span>';
		})
		->editColumn('hospital', function($hosp) use ($hospitals) {
			return $hospitals[$hosp->hospital] ?? '-';
		})
		->editColumn('hosp_status', function($hosp_s) {
			switch ($hosp_s->hosp_status) {
				case 'new':
					return '<span class="badge badge-pill badge-primary">'.ucfirst($hosp_s->hosp_status).'</span>';
					break;
				case 'updated':
					return '<span class="badge badge-pill badge-success">'.ucfirst($hosp_s->hosp_status).'</span>';
					break;
				default:
					return '-';
			}
		})
		->editColumn('lab_status', function($lab_s) {
			switch ($lab_s->lab_status) {
				case 'pending':
					return '<span class="badge badge-pill badge-primary">'.ucfirst($lab_s->lab_status).'</span>';
					break;
				case 'updated':
					return '<span class="badge badge-pill badge-success">'.ucfirst($lab_s->lab_status).'</span>';
					break;
				default:
					return '-';
			}
		})
		->editColumn('created_at', function($created_date) {
			return '<span class="badge badge-pill badge-light">'.$this->chDateTimeFormat($created_date->created_at).'</span>';
		})
		->addColumn(
			'action',
			'<button class="context-nav btn btn-custom-1 btn-sm" data-id="{{ $id }}">จัดการ <i class="fas fa-angle-down"></i></button>'
		)
		->rawColumns(['lab_code', 'hosp_status', 'lab_status', 'created_at', 'action']);
	}

	public function query(Patients $model) {
		$roleArr = auth()->user()->getRoleNames();
		$hospcode = auth()->user()->hospcode;
		switch ($roleArr[0]) {
			case 'admin':
				return $model->newQuery();
				break;
			case 'hospital':
				return $model->newQuery()->where('ref_user_hospcode', '=', $hospcode);
				break;
			case 'hosp-group':
				$hospGroup = UserBundleHosp::select('hosp_bundle')->whereUser_id(auth()->user()->id)->get();
				$hospGroupArr = explode(',', $hospGroup[0]->hosp_bundle);
				return $model->newQuery()->whereIn('hospital', $hospGroupArr);
				break;
			default:
				return redirect()->route('logout');
				break;
		}
	}

	public function html() {
		return $this->builder()
			->setTableId('patients-table')
			->columns($this->getColumns())
			->minifiedAjax()
			->dom('Bfrtip')
			->orderBy(0)
			->responsive(true)
			->parameters([
				'language'=>['url' => url('/assets/libs/datatables-1.10.20/i18n/thai.json')],
				'buttons' => ['excel'],
			])
			->lengthMenu([20])
			->buttons(
				//Button::make('export'),
				Button::make('print'),
				Button::make('reload')
			);
	}

	protected function getColumns() {
		return [
			Column::make('id')->title('ลำดับ'),
			Column::make('lab_code')->title('รหัส'),
			Column::make('first_name')->title('ชื่อ'),
			Column::make('last_name')->title('นามสกุล'),
			Column::make('hn')->title('HN'),
			Column::make('hospital')->title('โรงพยาบาล'),
			Column::make('hosp_status')->title('สถานะ รพ.'),
			Column::make('lab_status')->title('สถานะ แลป'),
			Column::make('created_at')->title('วันที่'),
			Column::computed('action')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass('text-left')
				->title('#')
		];
	}

	protected function filename() {
		return 'Patients_' . date('YmdHis');
	}

	protected function chDateTimeFormat($mysql_date_time = '0000-00-00 00:00:00') {
		if (!is_null($mysql_date_time) && !empty($mysql_date_time)) {
			$exp_date_time = explode(" ", $mysql_date_time);
			$exp_date = explode("-", $exp_date_time[0]);
			$str = $exp_date[2].'/'.$exp_date[1].'/'.$exp_date[0];
		} else {
			$str = null;
		}
		return $str;
	}
}
