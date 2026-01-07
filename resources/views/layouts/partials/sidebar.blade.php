<div class="list-group">
    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    @role('superadmin')
    <a href="{{ route('clients.list') }}" class="list-group-item list-group-item-action {{ request()->routeIs('clients.list') ? 'active' : '' }}">
        Clients
    </a>

    <a href="{{ route('clients.generatedUrlList') }}" class="list-group-item list-group-item-action {{ request()->routeIs('clients.generatedUrlList') ? 'active' : '' }}">
        URLs
    </a>
    @endrole

    @role('admin')
    <a href="{{ route('team.list') }}" class="list-group-item list-group-item-action {{ request()->routeIs('team.list') ? 'active' : '' }}">
        Team Members
    </a>

    <a href="{{ route('team.generatedUrls') }}" class="list-group-item list-group-item-action {{ request()->routeIs('team.generatedUrls') ? 'active' : '' }}">
        Team URLs
    </a>
    @endrole
</div>