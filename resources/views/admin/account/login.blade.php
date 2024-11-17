<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/vendors/iconfonts/mdi/css/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/vendors/css/vendor.addons.css" />
    <!-- endinject -->
    <!-- vendor css for this page -->
    <!-- End vendor css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/css/shared/style.css" />
    <!-- endinject -->
    <!-- Layout style -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/css/demo_1/style.css">
    <!-- Layout style -->
    <link rel="shortcut icon" href="{{ url("/") }}/admin_assets/images/favicon.ico" />
  </head>
  <body>
    <div class="authentication-theme auth-style_1">
      <div class="row">
        <div class="col-12 logo-section">
          <a href="../../index.html" class="logo">
            <img src="{{ url("/") }}/admin_assets/images/logo.svg" alt="logo" />
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-5 col-md-7 col-sm-9 col-11 mx-auto">
          <div class="grid">
            <div class="grid-body">
              <div class="row">
                <div class="col-lg-7 col-md-8 col-sm-9 col-12 mx-auto form-wrapper">
                  <form action="{{ route('loginSubmit') }}" method="POST">

                    @if (session()->has("error"))
                      <p class="text-danger mb-4">{{ session()->get("error") }}</p>
                    @endif

                    @csrf
                    <div class="form-group input-rounded">
                      <input type="email" class="form-control" placeholder="Email" name="email" />
                    </div>
                    <div class="form-group input-rounded">
                      <input type="password" class="form-control" placeholder="Password" name="password" />
                    </div>
                    <div class="form-inline">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="form-check-input" />Remember me <i class="input-frame"></i>
                        </label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"> Login </button>
                  </form>
                  <div class="signup-link">
                    <p>Don't have an account yet?</p>
                    <a href="#">Sign Up</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="auth_footer">
        <p class="text-muted text-center">© Label Inc 2019</p>
      </div>
    </div>
    <!--page body ends -->
    <!-- SCRIPT LOADING START FORM HERE /////////////-->
    <!-- plugins:js -->
    <script src="{{ url("/") }}/admin_assets/vendors/js/core.js"></script>
    <script src="{{ url("/") }}/admin_assets/vendors/js/vendor.addons.js"></script>
    <script src="{{ url("/") }}/admin_assets/js/template.js"></script>
  </body>
</html>