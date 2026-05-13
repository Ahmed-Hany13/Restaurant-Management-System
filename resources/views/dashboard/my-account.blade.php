@extends('layouts.app')
@section('title', 'My Account')
@section('content')
    <section class="content">
        <div class="container-fluid mt-3 mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Profile Update</h3>
                        </div>
                        @include('components.session-messages')
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="fw-semibold">Please fix the following:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            <form action="{{ route('UpdateMyAccount') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="profile_image">Profile Image</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image"
                                        value="{{ old('profile_image', $getRecord->profile_image) }}">
                                    @if (!empty($getRecord->profile_image))
                                        <br>
                                        <img src="{{ $getRecord->getProfileImage() }}" alt="">
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $getRecord->name) }}">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $getRecord->email) }}">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', $getRecord->phone) }}">
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Update Password</h3>
                        </div>

                        <div class="card-body">
                            @if (session('status') === 'password-updated')
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ __('Saved.') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif


                            <form action="{{ route('UpdateMyAccount') }}" method="POST">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" autocomplete="current-password">
                                    @if ($errors->updatePassword?->has('current_password'))
                                        <div class="text-danger mt-1">
                                            {{ $errors->updatePassword->first('current_password') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mt-3">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        autocomplete="new-password">
                                    @if ($errors->updatePassword?->has('password'))
                                        <div class="text-danger mt-1">{{ $errors->updatePassword->first('password') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mt-3">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" autocomplete="new-password">
                                    @if ($errors->updatePassword?->has('password_confirmation'))
                                        <div class="text-danger mt-1">
                                            {{ $errors->updatePassword->first('password_confirmation') }}</div>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
