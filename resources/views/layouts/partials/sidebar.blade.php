<div class="list-group">
    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    @role('superadmin|admin')
    <a href="{{ route('clients.list') }}" class="list-group-item list-group-item-action {{ request()->routeIs('clients.list') ? 'active' : '' }}">
        Clients
    </a>
    @endrole
</div>