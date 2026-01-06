@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-sm p-4">
                <div class="card-body">
                    <div class="display-1 text-danger mb-4">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h1 class="h3 fw-bold text-dark">Invitation Not Found</h1>
                    <p class="text-secondary mb-4">
                        This invitation link is invalid, has expired, or was already used. 
                        Please contact your administrator for a new link.
                    </p>
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4">Go to Home</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
