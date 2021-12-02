<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin5">
	<!-- Sidebar scroll-->
	<div class="scroll-sidebar">
		<!-- Sidebar navigation-->
		<nav class="sidebar-nav">
			<ul id="sidebarnav" class="p-t-30">
				@role('admin|hospital|lab|dmsc|hosp-group')
				<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('dashboard.index') }}" aria-expanded="false"><i class="mdi mdi-speedometer"></i><span class="hide-menu">แดชบอร์ด</span></a></li>
				@endrole
				@role('admin|hospital|hosp-group')
				<li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-hospital"></i><span class="hide-menu">โรงพยาบาล </span></a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item"><a href="{{ route('code.index') }}" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> สร้างรหัสใหม่</span></a></li>
						<li class="sidebar-item"><a href="{{ route('list') }}" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> รายการข้อมูล</span></a></li>
					</ul>
				</li>
				@endrole
				@role('admin|lab|dmsc')
				<li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-flask"></i><span class="hide-menu">ห้องปฏิบัติการ </span></a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item"><a href="{{ route('lab') }}" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> รายการข้อมูล</span></a></li>
					</ul>
				</li>
				@endrole
				@role('admin|hospital|lab|dmsc|hosp-group')
				<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('export-csv') }}" aria-expanded="false"><i class="mdi mdi-export"></i><span class="hide-menu">ส่งออกข้อมูล</span></a></li>
				@endrole
				@role('admin')
				<li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-google-maps"></i><span class="hide-menu">แผนที่ </span></a>
					<ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('spread') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">Cluster Map</span></a></li>
						<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('chart') }}" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">Dot Map</span></a></li>
					</ul>
				</li>

				<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('counter') }}" aria-expanded="false"><i class="fas fa-chart-line"></i><span class="hide-menu">สถิติการใช้งานเว็บไซต์</span></a></li>
				<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('users.index') }}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">จัดการผู้ใช้</span></a></li>
				@endrole
			</ul>
		</nav>
		<!-- End Sidebar navigation -->
	</div>
	<!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
