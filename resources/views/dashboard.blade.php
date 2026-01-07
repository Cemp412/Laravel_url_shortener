@role('superadmin')
    @include('dashboards.superAdminDashboard');
@endrole

@role('admin')
    @include('dashboards.adminDashboard');
@endrole

@role('member')
    @include('dashboards.memberDashboard');
@endrole

