@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">All Users</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bookings</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->bookings_count }}</td>
                    <td>
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="d-flex gap-1">
                            @csrf
                            @method('PUT')
                            <select name="role" class="form-select form-select-sm">
                                <option value="user"  {{ $user->role === 'user'  ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @else
                            <span class="text-muted small">You</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
