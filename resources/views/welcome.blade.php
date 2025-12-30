<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/welcome.css') }}" rel="stylesheet">
</head>
<body>
    <div class="main-div bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row justfy-content-center align-items-center">
                <div class="col-12 text-center">
                    <div class="mb-4">
                        
                        <h1 class="display-3 font-weight-bold"><i class="fas fa-link fa-4x text-white mb-3">🔗</i>&nbsp;{{ config('app.name') }}</h1>
                        <p class="lead mb-5">A reliable and efficient service to generate short URLs.</p>               
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 shadow-sm">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-2 mb-0 p-0">
        <div class="ml-4 text-center text-sm text-gray-500">
            &copy; <?php echo date("Y"); ?> {{ config('app.name') }}. All rights reserved.
        </div>
    </footer>
</body>
</html>