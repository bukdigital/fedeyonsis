<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8" />
        <title>FYS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="FYS" name="description" />
        <meta content="BUK  | DIGITAL SOLUTIONS" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        @stack('styles')
        <!-- App css -->
        <link href="{{asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body>

        <!-- Top Bar Start -->
        @include('layouts.topbar')
        <!-- Top Bar End -->
        @include('layouts.auth')
        <!--end page-wrapper-img-->

        <div class="page-wrapper">
            <div class="page-wrapper-inner">

                <!-- Navbar Custom Menu -->
                @include('layouts.navmenu')
                <!-- end left-sidenav-->
            </div>
            <!--end page-wrapper-inner -->
            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- container -->

                @include('layouts.footer')
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="{{asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{asset('assets/js/waves.min.js') }}"></script>
        <script src="{{asset('assets/js/jquery.slimscroll.min.js') }}"></script>

        @stack('js')
        <!-- App js -->
        <script src="{{asset('assets/js/app.js') }}"></script>

    </body>
</html>
