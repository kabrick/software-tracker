<div class="card">
    <div class="card-body">
        @can('role_create')<a href="{{ route('roles.create') }}" class="btn btn-primary btn-rounded btn-sm text-white">Add Role</a>@endcan
        @can('role_list')<a href="{{ route('roles.index') }}" class="btn btn-primary btn-rounded btn-sm text-white">Manage Roles</a>@endcan
    </div>
</div>

<br>
