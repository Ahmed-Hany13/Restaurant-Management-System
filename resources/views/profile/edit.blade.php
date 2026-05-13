@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{ __('Profile') }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
<div class="card-header bg-primary">
                                <h3 class="card-title">Profile details</h3>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.view-profile-details')
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-4">
<div class="card-header bg-primary">
                                <h3 class="card-title">Update information</h3>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-profile-information-form-edit')
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card mb-4">
<div class="card-header bg-primary">
                                <h3 class="card-title">Change password</h3>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card mb-4 border border-danger border-opacity-25">
<div class="card-header bg-danger">
                                <h3 class="card-title">Delete account</h3>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


