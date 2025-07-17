<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Appointment System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .bg-cover {
            background-image: url('Muhimbili.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .btn-custom {
            width: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="bg-cover">
        <div class="overlay"></div>
        <div class="content">
            <h1 class="mb-4 fw-bold">Doctor Appointment System</h1>
            <div>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-lg btn-custom">Admin </a>
                <a href="{{ route('doctor.login') }}" class="btn btn-outline-light btn-lg btn-custom">Doctor </a>
                <a href="{{ route('patient.login') }}" class="btn btn-outline-light btn-lg btn-custom">Patient </a>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS (Optional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
