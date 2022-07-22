<div class="card">
    <div class="card-body">
        @can('users_create')<a href="{{ route('user.create') }}" class="btn btn-primary btn-rounded btn-sm text-white">Add User</a>@endcan
        @can('users_list')<a href="{{ route('user.index') }}" class="btn btn-primary btn-rounded btn-sm text-white">Manage Users</a>@endcan
    </div>
</div>

<br>
