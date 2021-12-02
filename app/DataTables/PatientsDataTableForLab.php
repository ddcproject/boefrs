<?php
namespace App\DataTables;

use App\Patients;
use App\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Services\DataTable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;

class PatientsDataTableForLab extends DataTable
{
	public function dataTable($query) {
		return datatables()
			->eloquent($query)
			->editColumn('lab_code', function($code) {
				return '<span class="font-1">'.$code->lab_code.'</span>';
			})
			->editColumn('hospital', function($hosp) {
				return $hosp->hospital ?? '-';
			})
			->editColumn('hosp_status', function($hosp_s) {
				switch ($hosp_s->hosp_status) {
					case 'new':
						return '<span class="badge badge-pill badge-cyan font-0875">'.ucfirst($hosp_s->hosp_status).'</span>';
						break;
					case 'updated':
						return '<span class="badge badge-pill badge-success font-0875">'.ucfirst($hosp_s->hosp_status).'</span>';
						break;
					default:
						return '-';
				}
			})
			->editColumn('lab_status', function($lab_s) {
				switch ($lab_s->lab_status) {
					case 'pending':
						return '<span class="badge badge-pill badge-cyan font-0875">'.ucfirst($lab_s->lab_status).'</span>';
						break;
					case 'updated':
						return '<span class="badge badge-pill badge-success font-0875">'.ucfirst($lab_s->lab_status).'</span>';
						break;
					default:
						return '-';
				}
			})
			->addColumn(
				'action',
				'<button class="context-nav btn btn-custom-1 btn-sm" data-id="{{ $id }}">จัดการ <i class="fas fa-angle-down"></i></button>'
			)
			->rawColumns(['lab_code', 'hosp_status', 'lab_status', 'action']);
	}

	public function query(Patients $model) {
		$roleArr = auth()->user()->getRoleNames();
		$hospcode = auth()->user()->hospcode;
		switch ($roleArr[0]) {
			case 'admin':
				return $model->newQuery()->orderBy('id', 'DESC');;
				break;
			case 'lab':
				return $model->newQuery()->where('ref_user_hospcode', '=', $hospcode)->orderBy('id', 'DESC');
				break;
			case 'dmsc':
				return $model->newQuery()->orderBy('id', 'DESC');;
				break;
			default:
				return redirect()->route('logout');
		}

	}

	public function html() {
		return $this->builder()
			->setTableId('patient-lab-table')
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
				//Button::make('create'),
				//Button::make('export'),
				Button::make('print'),
				//Button::make('reset'),
				Button::make('reload')
			);
	}

	protected function getColumns() {
		return [
			Column::make('id')->title('ลำดับ'),
			Column::make('first_name')->title('ชื่อ'),
			Column::make('last_name')->title('นามสกุล'),
			Column::make('hn')->title('HN'),
			Column::make('lab_code')->title('รหัส'),
			Column::make('hospital')->title('รหัส รพ.'),
			Column::make('hosp_status')->title('สถานะ รพ.'),
			Column::make('lab_status')->title('สถานะ แลป'),
			Column::computed('action')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass('text-center')
				->title('#')
		];
	}

	protected function filename() {
		return 'PatientsDataTableForLab_' . date('YmdHis');
	}
}
