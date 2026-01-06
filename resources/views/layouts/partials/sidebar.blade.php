<div class="list-group">
    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    @role('superadmin')
    <a href="{{ route('clients.list') }}" class="list-group-item list-group-item-action {{ request()->routeIs('clients.list') ? 'active' : '' }}">
        Clients
    </a>
    @endrole

    @role('admin')
    <a href="{{ route('team.list') }}" class="list-group-item list-group-item-action {{ request()->routeIs('team.list') ? 'active' : '' }}">
        Team Members
    </a>
    @endrole
</div>