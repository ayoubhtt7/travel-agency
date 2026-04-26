@extends('layouts.app')

@section('content')

<div class="container py-4" style="max-width: 700px;">

    <h2 class="mb-4">My Profile</h2>

    {{-- Profile Updated Success --}}
    @if(session('status') === 'profile-updated')
        <div class="alert alert-success">Profile updated successfully.</div>
    @endif

    {{-- Update Profile Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Profile Information</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning">
                        Your email address is unverified.
                        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0">Resend verification email.</button>
                        </form>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Update Password</div>
        <div class="card-body">

            @if(session('status') === 'password-updated')
                <div class="alert alert-success">Password updated successfully.</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password"
                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                           autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                           autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-warning">Update Password</button>
            </form>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="card border-danger shadow-sm">
        <div class="card-header fw-bold text-danger">Delete Account</div>
        <div class="card-body">
            <p class="text-muted">Once your account is deleted, all data will be permanently removed.</p>

            <form method="POST" action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
                @csrf
                @method('DELETE')

                <div class="mb-3">
                    <label class="form-label">Enter your password to confirm</label>
                    <input type="password" name="password"
                           class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                           placeholder="Password">
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger">Delete My Account</button>
            </form>
        </div>
    </div>

</div>

@endsection
