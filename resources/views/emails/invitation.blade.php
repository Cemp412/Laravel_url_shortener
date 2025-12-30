<!DOCTYPE html>

<body>
    <title>Invitation email from {{ config('constants.app.name') }}</title>
    <h1>Hello {{ $invitation->name }}</h1>
    <p>You have been invited to join our platform.</p>
    <p>Click the link below to accept: </p>
    <a href="{{ url('/accept-invitation/' . $invitation->token) }}">Join now</a>
</body>
</html>