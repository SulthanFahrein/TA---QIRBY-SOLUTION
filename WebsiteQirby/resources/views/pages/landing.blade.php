<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing Page Admin Website</title>
    <link rel="icon" type="image/x-icon" href="assets/img/avatar/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <style>
        body {
            background-color: #021622;
            color: white;
        }

        .navbar,
        .header,
        .stats {
            background-color: #021622;
        }
    </style>
</head>

<body>
    <nav class="mb-70 navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="">
                <img src="assets/img/avatar/logo.jpg" height="76" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-secondary" href="login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="header mb-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="jumbo-header mb-30">
                        Admin Website <br>
                        Qirby
                    </h1>
                    <p class="paragraph mb-30">
                        Admin Website Qirby adalah sebuah platform yang dirancang khusus untuk para admin dalam
                        mengelola properti, jadwal, dan pengguna yang ada di Aplikasi Qirby. Dengan antarmuka
                        dan fitur yang lengkap, Admin Website Qirby memudahkan pengelolaan dan pemantauan
                        operasional secara efisien dan efektif.
                    </p>
                    <div class="row stats text-center">
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/img/1.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
</body>

</html>