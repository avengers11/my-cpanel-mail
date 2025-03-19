<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Template</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/vendors/iconfonts/mdi/css/materialdesignicons.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/css/shared/style.css">
    <!-- endinject -->
    <!-- Layout style -->
    <link rel="stylesheet" href="{{ url("/") }}/admin_assets/css/demo_1/style.css">
    <!-- Layout style -->
    <link rel="shortcut icon" href="../asssets/images/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('css')
  </head>
  <body class="header-fixed">

    <!-- partial:partials/_header.html -->
    <nav class="t-header">
      <div class="t-header-brand-wrapper">
          <a href="" class="d-none">
              <img class="logo" src="{{ asset("logo/logo.png") }}" alt="">
              <img class="logo-mini" src="{{ url("/") }}/admin_assets/images/logo_mini.svg" alt="">
          </a>
      </div>
      <div class="t-header-content-wrapper">
          <div class="t-header-content">
              <button class="t-header-toggler t-header-mobile-toggler d-block d-lg-none">
                  <i class="mdi mdi-menu"></i>
              </button>
              <form action="#" class="t-header-search-box">
                  <div class="input-group">
                      <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search" autocomplete="off" />
                      <button class="btn btn-primary" type="submit"><i class="mdi mdi-arrow-right-thick"></i></button>
                  </div>
              </form>
              <ul class="nav ml-auto">
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="#" id="notificationDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-bell-outline mdi-1x"></i>
                      </a>
                      <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="notificationDropdown">
                          <div class="dropdown-header">
                              <h6 class="dropdown-title">Notifications</h6>
                              <p class="dropdown-title-text">You have 4 unread notification</p>
                          </div>
                          <div class="dropdown-body">
                              <div class="dropdown-list">
                                  <div class="icon-wrapper rounded-circle bg-inverse-primary text-primary">
                                      <i class="mdi mdi-alert"></i>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Storage Full</small>
                                      <small class="content-text">Server storage almost full</small>
                                  </div>
                              </div>
                              <div class="dropdown-list">
                                  <div class="icon-wrapper rounded-circle bg-inverse-success text-success">
                                      <i class="mdi mdi-cloud-upload"></i>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Upload Completed</small>
                                      <small class="content-text">3 Files uploded successfully</small>
                                  </div>
                              </div>
                              <div class="dropdown-list">
                                  <div class="icon-wrapper rounded-circle bg-inverse-warning text-warning">
                                      <i class="mdi mdi-security"></i>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Authentication Required</small>
                                      <small class="content-text">Please verify your password to continue using cloud services</small>
                                  </div>
                              </div>
                          </div>
                          <div class="dropdown-footer">
                              <a href="#">View All</a>
                          </div>
                      </div>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="#" id="messageDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-message-outline mdi-1x"></i>
                          <span class="notification-indicator notification-indicator-primary notification-indicator-ripple"></span>
                      </a>
                      <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="messageDropdown">
                          <div class="dropdown-header">
                              <h6 class="dropdown-title">Messages</h6>
                              <p class="dropdown-title-text">You have 4 unread messages</p>
                          </div>
                          <div class="dropdown-body">
                              <div class="dropdown-list">
                                  <div class="image-wrapper">
                                      <img class="profile-img" src="{{ url("/") }}/admin_assets/images/profile/male/image_1.png" alt="profile image">
                                      <div class="status-indicator rounded-indicator bg-success"></div>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Clifford Gordon</small>
                                      <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                  </div>
                              </div>
                              <div class="dropdown-list">
                                  <div class="image-wrapper">
                                      <img class="profile-img" src="{{ url("/") }}/admin_assets/images/profile/female/image_2.png" alt="profile image">
                                      <div class="status-indicator rounded-indicator bg-success"></div>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Rachel Doyle</small>
                                      <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                  </div>
                              </div>
                              <div class="dropdown-list">
                                  <div class="image-wrapper">
                                      <img class="profile-img" src="{{ url("/") }}/admin_assets/images/profile/male/image_3.png" alt="profile image">
                                      <div class="status-indicator rounded-indicator bg-warning"></div>
                                  </div>
                                  <div class="content-wrapper">
                                      <small class="name">Lewis Guzman</small>
                                      <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                  </div>
                              </div>
                          </div>
                          <div class="dropdown-footer">
                              <a href="#">View All</a>
                          </div>
                      </div>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="#" id="appsDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-apps mdi-1x"></i>
                      </a>
                      <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="appsDropdown">
                          <div class="dropdown-header">
                              <h6 class="dropdown-title">Apps</h6>
                              <p class="dropdown-title-text mt-2">Authentication required for 3 apps</p>
                          </div>
                          <div class="dropdown-body border-top pt-0">
                              <a class="dropdown-grid">
                                  <i class="grid-icon mdi mdi-jira mdi-2x"></i>
                                  <span class="grid-tittle">Jira</span>
                              </a>
                              <a class="dropdown-grid">
                                  <i class="grid-icon mdi mdi-trello mdi-2x"></i>
                                  <span class="grid-tittle">Trello</span>
                              </a>
                              <a class="dropdown-grid">
                                  <i class="grid-icon mdi mdi-artstation mdi-2x"></i>
                                  <span class="grid-tittle">Artstation</span>
                              </a>
                              <a class="dropdown-grid">
                                  <i class="grid-icon mdi mdi-bitbucket mdi-2x"></i>
                                  <span class="grid-tittle">Bitbucket</span>
                              </a>
                          </div>
                          <div class="dropdown-footer">
                              <a href="#">View All</a>
                          </div>
                      </div>
                  </li>
              </ul>
          </div>
      </div>
    </nav>
    <!-- partial -->

    <div class="page-body">
        <!-- partial:partials/_sidebar.html -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="display-avatar animated-avatar">
                    <img class="profile-img img-lg rounded-circle" src="{{ asset("logo/logo.png") }}" alt="profile image">
                </div>
                <div class="info-wrapper">
                    <p class="user-name">{{ auth()->user()->name }}</p>
                    <h6 class="display-income">{{ auth()->user()->email }}</h6>
                </div>
            </div>
            <ul class="navigation-menu">
                <li class="nav-category-divider">MAIN</li>
                <li @if(Route::is("admin.dashboard.index")) class="active" @endif>
                    <a href="{{ route("admin.dashboard.index") }}">
                        <span class="link-title">Dashboard</span>
                        <i class="mdi mdi-gauge link-icon"></i>
                    </a>
                </li>
                @if (\Auth::user()->role == "admin")
                    <li @if(Route::is("admin.email.index")) class="active" @endif>
                        <a href="{{ route("admin.email.index") }}">
                            <span class="link-title">Email</span>
                            <span class="mdi mdi-email-alert link-icon"></span>

                        </a>
                    </li>
                    <li @if(Route::is("admin.card.index")) class="active" @endif>
                        <a href="{{ route("admin.card.index") }}">
                            <span class="link-title">Card Generator</span>
                            <span class="mdi mdi-creation link-icon"></span>
                        </a>
                    </li>
                    <li @if(Route::is("admin.card.add")) class="active" @endif>
                        <a href="{{ route("admin.card.add") }}">
                            <span class="link-title">Add Card</span>
                            <span class="mdi mdi-credit-card link-icon"></span>
                        </a>
                    </li>
                    <li @if(Route::is("admin.card.remove")) class="active" @endif>
                        <a href="{{ route("admin.card.remove") }}">
                            <span class="link-title">Remove Cards</span>
                            <span class="mdi mdi-credit-card link-icon"></span>
                        </a>
                    </li>
                    <li @if(Route::is("admin.card.amazonOrder")) class="active" @endif>
                        <a href="{{ route("admin.card.amazonOrder") }}">
                            <span class="link-title">Amazon Order</span>
                            <span class="mdi mdi-cart-arrow-down link-icon"></span>
                        </a>
                    </li>

                    <li @if(Route::is("admin.audible.orderView")) class="active" @endif>
                        <a href="{{ route("admin.audible.orderView") }}">
                            <span class="link-title">Audible Order</span>
                            <span class="mdi mdi-cart-arrow-down link-icon"></span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route("admin.card.openBrowser") }}">
                        <span class="link-title">Open Browser</span>
                        <span class="mdi mdi-open-in-app link-icon"></span>
                    </a>
                </li>

                @if (\Auth::user()->role == "review" || \Auth::user()->role == "admin")
                    <li @if(Route::is("admin.review.all")) class="active" @endif>
                        <a href="{{ route("admin.review.all") }}">
                            <span class="link-title">All Review</span>
                            <i class="mdi mdi-calendar-check link-icon"></i>
                        </a>
                    </li>

                    <li @if(Route::is("admin.review.todaysTask")) class="active" @endif>
                        <a href="{{ route("admin.review.todaysTask") }}">
                            <span class="link-title">Today's Task</span>
                            <span class="mdi mdi-checkbox-multiple-marked-circle-outline link-icon"></span>
                        </a>
                    </li>
                    <li @if(Route::is("admin.review.completedTask")) class="active" @endif>
                        <a href="{{ route("admin.review.completedTask") }}">
                            <span class="link-title">Completed Task</span>
                            <span class="mdi mdi-checkbox-multiple-marked-circle-outline link-icon"></span>
                        </a>
                    </li>
                @endif
                
            </ul>
            <div class="sidebar-upgrade-banner"></div>
        </div>

        <div class="page-content-wrapper">
            <div class="content-viewport">
                @yield('master')
            </div>
        </div>
    </div>

    
    <!--page body ends -->
    <script src="{{ url("/") }}/admin_assets/vendors/js/core.js"></script>
    <!-- Vendor Js For This Page Ends-->
    <script src="{{ url("/") }}/admin_assets/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="{{ url("/") }}/admin_assets/vendors/chartjs/Chart.min.js"></script>
    <script src="{{ url("/") }}/admin_assets/js/charts/chartjs.addon.js"></script>
    <!-- Vendor Js For This Page Ends-->
    <!-- build:js -->
    <script src="{{ url("/") }}/admin_assets/js/template.js"></script>
    <script src="{{ url("/") }}/admin_assets/js/dashboard.js"></script>
    <!-- endbuild -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    
        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    
        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>

    @stack('js')
  </body>
</html>