<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login | Portal DTE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/input.css') }}" rel="stylesheet">
  @stack('styles')
  <style>
    .form-control:focus {
      border: 2px solid #1a73e8 !important;
      box-shadow: none;
    }

    .radius {
      border-top-right-radius: 0px;
      border-bottom-right-radius: 0px;
    }

    .loading-spinner {
      position: relative;
    }

    .loading-spinner::after {
      content: "";
      position: absolute;
      right: 10px;
      top: 50%;
      width: 16px;
      height: 16px;
      margin-top: -8px;
      border: 2px solid #ccc;
      border-top-color: #333;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
    .alert-dismissible .btn-close{
      padding: 8px !important;
    }
  </style>
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-4 d-flex flex-column align-items-center justify-content-center">
              @if (session('error'))
              <div class="alert alert-danger alert-dismissible fade show py-1" role="alert" style="width: 360px;">
                <i class="bi bi-exclamation-triangle me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif
              <div class="card mb-3" style="max-width: 360px;">
                <div class="card-body p-3">

                  <div
                    style="border-bottom: 5px !important; display: flex; justify-content: center;margin-bottom: 20px">
                    <img src="assets/img/logo.png" alt="" class="img-responsive log" width="80" height="80">
                  </div>

                  <form class="row g-3 needs-validation" action="{{ route('app.login.auth') }}" method="POST">
                    @csrf
                    <div class="col-12">
                      <div class="input-group has-validation">
                        <input type="text" name="usuario" class="form-control radius" id="usuario" placeholder="Usuario"
                          required>
                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="input-group has-validation">
                        <input type="password" name="clave_user" class="form-control radius" id="clave_user"
                          placeholder="Clave" required>
                        <span class="input-group-text" id="icon-show-password" style="cursor: pointer"><i
                            class="bi bi-shield-lock"></i></span>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100 btn-sm" type="submit">INICIAR SESIÓN</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->
  <script src="{{ asset('app/modules/usuario/login.js') }}"></script>
</body>

</html>