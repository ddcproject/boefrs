@extends('layouts.index')
@section('custom-style')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/jquery-contextmenu/dist/jquery.contextMenu.min.css') }}">
<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/datatables.min.css') }}">
<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/Buttons-1.6.1/css/buttons.bootstrap4.min.css') }}">
<link rel='stylesheet' href="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/css/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/libs/toastr/build/toastr.min.css') }}">
@endsection
@section('internal-style')
<style>
	.page-wrapper {
		background: white !important;
	}
	.dataTables_wrapper, .dataTables_wrapper .badge {
		font-family: 'Fira-code' !important;
		font-size: 1em;
	}
	.dataTables_wrapper .badge {
		padding: 6px;
		font-size: .875em;
		text-align: left;
	}
	table.dataTable th {border-top: none;}
	table.dataTable tr.odd {background-color: #F6F6F6;  border:1px lightgrey;}
	table.dataTable tr.even{background-color: white; border:1px lightgrey;}
</style>
@endsection
@section('meta-token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('contents')
<div class="page-breadcrumb bg-light">
	<div class="row">
		<div class="col-12 d-flex no-block align-items-center">
			<h4 class="page-title"><span style="display:none;">Print</span></h4>
			<div class="ml-auto text-right">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Patient</a></li>
						<li class="breadcrumb-item active" aria-current="page">List</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div class="d-md-flex align-items-center" style="border-bottom:1px solid #EAEAEA">
				<div>
					<h4 class="card-title">รายการข้อมูล โครงการเฝ้าระวังเชื้อไวรัสก่อโรคระบบทางเดินหายใจ</h4>
					<h5 class="card-subtitle">Flu-BOE</h5>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body" style="margin:10px 0 0 0;padding:0;">
							<div id="patient_data">
								{{ $dataTable->table() }}
							</div>
						</div><!-- card body -->
					</div><!-- card -->
				</div><!-- column -->
			</div><!-- row -->
		</div><!-- card body -->
	</div><!-- card -->
</div><!-- contrainer -->
@endsection
@section('bottom-script')
<script src="{{ URL::asset('assets/libs/bootstrap-validate-2.2.0/dist/bootstrap-validate.js') }}"></script>
<script src="{{ URL::asset('assets/libs/jquery-contextmenu/dist/jquery.contextMenu.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/jquery-contextmenu/dist/jquery.ui.position.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/DataTables-1.10.20/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/Buttons-1.6.1/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables-1.10.20/Responsive-2.2.3/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
{{ $dataTable->scripts() }}
<script>
$(document).ready(function() {
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
	$.contextMenu({
		selector: '.context-nav',
		trigger: 'left',
		className: 'data-title',
		callback: function(key, options) {
			var id = $(this).data('id');
			switch (key) {
				case 'lab':
					let lurl = '{{ route('createLab', ':id') }}';
					lurl = lurl.replace(':id', id);
					window.open(lurl, '_self');
					break;
				case 'result':
					let rurl = '{{ route('viewLab', ':id') }}';
					rurl = rurl.replace(':id', id);
					window.open(rurl, '_self');
					break;
				case 'print':
					let purl = '{{ route('viewprintpdfforlab', ':id') }}';
					purl = purl.replace(':id', id);
					window.open(purl, '_self');
					break;
				case 'delete':
					alert('Soon!');
				//		$('#fid').val(id);
				//		$('.delete-context').modal('show');
					break;
				default:
					break;
			}
		},
		items: {
			"lab": {name: "ลงผลแลป", icon: "fas fa-edit"},
			"result": {name: "ดูข้อมูลผลแลป", icon: "fas fa-vial"},
			"print": {name: "พิมพ์ผลแลป", icon: "fas fa-print"},
			"delete": {name: "ลบข้อมูล", icon: "fas fa-trash-alt"},
			"sep1": "---------",
			"quit": {name: "ปิด", icon: function($element, key, item){ return 'context-menu-icon context-menu-icon-quit'; }}
		}
	});
});
</script>
@endsection
