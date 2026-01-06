<!DOCTYPE html>

<body>
    <title>Invitation email from {{ config('constants.app.name') }}</title>
    <h1>Hello {{ $invitation->name }}</h1>
    <p>You have been invited to join our platform.</p>
    <p>Click the link below to accept: </p>
    <div class="text-center">
        <a href="{{ $url }}" class="btn btn-dark px-4 py-2 fw-bold text-white" target="_blank" rel="noopener noreferrer">
            Join Now
        </a>
    </div>
    
    <br>
    <hr class="border-top my-4 opacity-50">
    <p class="text-muted mb-1">
        <small >In any case if above button is not working you can directly copy and paste the URL below into your web browser.</small>
    </p>
    <p class="small text-break">
        <a href="{{ $url }}" target="_blank" class="text-primary text-decoration-none">{{ $url }}</a>
    </p>
    <hr class="border-bottom my-4 opacity-50">

    <p>Thanks,
        <br>
        {{ config('app.name') }}
    </p>
</body>
</html>