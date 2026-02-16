<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from phpstack-1384472-5121645.cloudwaysapps.com/template/html/axelit/template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 01 Jan 2026 04:47:59 GMT -->

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <meta content="la-themes" name="author">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
    <title>@yield('title')</title>
    <!--font-awesome-css-->
    <link href="{{ asset('assets') }}/vendor/fontawesome/css/all.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">

    <!-- iconoir icon css  -->
    <link href="{{ asset('assets') }}/vendor/ionio-icon/css/iconoir.css" rel="stylesheet">

    <!-- Animation css -->
    <link href="{{ asset('assets') }}/vendor/animation/animate.min.css" rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets') }}/vendor/tabler-icons/tabler-icons.css" rel="stylesheet" type="text/css">

    <!--flag Icon css-->
    <link href="{{ asset('assets') }}/vendor/flag-icons-master/flag-icon.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets') }}/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    <link href="{{ asset('assets') }}/vendor/apexcharts/apexcharts.css" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="{{ asset('assets') }}/vendor/simplebar/simplebar.css" rel="stylesheet" type="text/css">

    <!-- slick css -->
    <link href="{{ asset('assets') }}/vendor/slick/slick.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/slick/slick-theme.css" rel="stylesheet">

    <!-- filepond css -->
    <link href="{{ asset('assets') }}/vendor/filepond/filepond.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/filepond/image-preview.min.css" rel="stylesheet">

    <!-- App css-->
    <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets') }}/css/responsive.css" rel="stylesheet" type="text/css">
    @stack('styles')
</head>

<body>
    <div class="app-wrapper">
        <div class="loader-wrapper">
            <div class="app-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Menu Navigation starts -->

        @include('ui.sidebar')
        <div class="app-content ">
            <div class="">
                @include('ui.header')
                <main>
                    @yield('content')
                </main>

            </div>
        </div>
        <!-- Body main section ends -->


        <!-- tap on top -->
        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-chevron-up"></i>
            </span>
        </div>


        <!-- Footer Section starts-->
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <ul class="footer-text">
                            <li>
                                <p class="mb-0">Copyright © {{ date('Y') }} Vishwas Tech </p>
                            </li>
                            <li><a href="#"> V1.0.0 </a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="footer-text text-end">
                            <li><a href="mailto:vshivsamb@gmail.com"> Need Help <i class="ti ti-help"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Section ends-->
    </div>


    <!-- modal -->


    <!--customizer-->
    <div id="customizer"></div>

    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.6.3.min.js"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('assets') }}/vendor/simplebar/simplebar.js"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets') }}/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/apexcharts/column/dayjs.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/apexcharts/column/quarterOfYear.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/apexcharts/timelinechart/moment.min.js"></script>

    <!-- Customizer js-->
    <script>
        var customizer_url = "{{ asset('assets/customizer.txt') }}";
    </script>
    <script src="{{ asset('assets') }}/js/customizer.js"></script>

    <!-- phosphor js -->
    <script src="{{ asset('assets') }}/vendor/phosphor/phosphor.js"></script>

    <!-- slick-file -->
    <script src="{{ asset('assets') }}/vendor/slick/slick.min.js"></script>

    <!-- filepond -->
    <script src="{{ asset('assets') }}/vendor/filepond/file-encode.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/filepond/validate-size.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/filepond/validate-type.js"></script>
    <script src="{{ asset('assets') }}/vendor/filepond/exif-orientation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/filepond/image-preview.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/filepond/filepond.min.js"></script>



    <!-- App js-->
    <script src="{{ asset('assets') }}/js/script.js"></script>

    <script>
        $(document).ready(function() {
            var $nav = $(".header-toggle");
            if ($nav.length) {
                $nav.click();
            }
        });
    </script>

    @stack('scripts')


</body>



</html>
