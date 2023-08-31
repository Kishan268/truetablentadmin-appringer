<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="index,follow">

    <title>{{ env('APP_NAME') }} | Work Profile</title>

    <!-- Bootstrap Core CSS -->
    {{-- <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css"> --}}
    {{-- {{ style(mix('css/frontend.css')) }} --}}
    {{ style('theme/plugins/bootstrap/css/bootstrap.min.css') }}

    <!-- Icons -->
    {{ style('theme/plugins/icons/css/icons.css') }}

    <!-- Animate -->
    {{ style('css/animate.css') }}
    {{-- {{ style("theme/plugins/animate/animate.css" rel="stylesheet"> --}}

    <!-- Bootsnav -->
    {{ style('theme/plugins/bootstrap/css/bootsnav.css') }}

    <!-- Custom style -->
    {{ style('theme/css/style.css') }}
    {{ style('theme/css/responsiveness.css') }}

    <!-- Custom Color -->
    {{ style('theme/css/skin/default.css') }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .dropdown-item {
            text-align: center !important;
        }
    </style>
</head>

<body class="blue-skin">

    <!-- ======================= Start Navigation ===================== -->
    <nav class="navbar navbar-default navbar-mobile navbar-fixed light bootsnav">
        <div class="container" id='app'>

            <!-- Start Logo Header Navigation -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="/">
                    <img src="/img/backend/brand/logo.png" class="logo logo-display" alt="">
                    <img src="/img/backend/brand/logo.png" class="logo logo-scrolled" width="120" alt="">
                </a>

            </div>
            <!-- End Logo Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">

                <ul class="nav navbar-nav navbar-left" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="nav-item"><a href="javascript:void(0);"
                            class="nav-link comingSoon {{ active_class(Route::is('frontend.user.dashboard')) }}">About
                            Us</a></li>
                    <li class="nav-item"><a href="javascript:void(0);"
                            class="nav-link comingSoon {{ active_class(Route::is('frontend.user.dashboard')) }}">Solutions</a>
                    </li>
                    <li class="nav-item"><a href="javascript:void(0);"
                            class="nav-link comingSoon {{ active_class(Route::is('frontend.user.dashboard')) }}">Pricing</a>
                    </li>
                    <li class="nav-item mr-5"><a href="javascript:void(0);"
                            class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">Contact Us</a>
                    </li>
                    {{-- <li class="nav-item"> --}}
                    {{-- <a class="nav-link" href="/">Home</a> --}}
                    {{-- </li> --}}
                    {{-- <li class="dropdown"> --}}
                    {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">Home</a> --}}
                    {{-- <ul class="dropdown-menu animated fadeOutUp">
                                <li><a href="index.html">Home 1</a></li>
                                <li><a href="home-2.html">Home 2</a></li>
                                <li><a href="home-3.html">Home 3</a></li>
                                <li><a href="home-4.html">Home 4</a></li>
                                <li><a href="freelancer.html">Freelancer</a></li>
                            </ul> --}}
                    {{-- </li> --}}


                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @guest
                        <li class="br-right"><a href="{{ route('frontend.auth.login') }}"><i
                                    class="login-icon ti-user"></i>Login</a></li>
                        <li class="sign-up"><a class="btn-signup red-btn" href="signup.html"><span
                                    class="ti-briefcase"></span>Sign Up</a></li>
                        {{-- <li class="nav-item"><a href="{{route('frontend.auth.login')}}" class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}">@lang('navs.frontend.login')</a></li> --}}

                        @if (config('access.registration'))
                            <li class="nav-item mt-2">
                                /
                            </li>
                            <li class="nav-item"><a href="{{ route('frontend.auth.register', ['type' => 'candidate']) }}"
                                    class="nav-link {{ active_class(Route::is('frontend.auth.register')) }}">@lang('navs.frontend.register')</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown mr-4">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser"
                                data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">{{ $logged_in_user->name }}</a>

                            <ul class="dropdown-menu animated fadeOutUp" aria-labelledby="navbarDropdownMenuUser">
                                @can('view backend')
                                    <li><a href="{{ route('admin.dashboard') }}" class="dropdown-item">@lang('navs.frontend.user.administration')</a>
                                    </li>
                                @endcan

                                {{-- Candidate Navs --}}
                                @if ($logged_in_user->hasRole('candidate'))
                                    <li><a href="{{ route('frontend.candidate.myJobs') }}"
                                            class="dropdown-item {{ active_class(Route::is('frontend.candidate.myJobs')) }}">My
                                            Jobs</a></li>
                                    <li><a href="{{ route('frontend.user.work_profile') }}"
                                            class="dropdown-item {{ active_class(Route::is('frontend.user.work_profile')) }} {{ active_class(Route::is('frontend.candidate.personalProfile')) }}">My
                                            Work Profile</a></li>
                                @endif

                                {{-- Corporate Navs --}}
                                @if ($logged_in_user->hasRole('company admin'))
                                    <li><a href="{{ route('frontend.company.jobs') }}"
                                            class="dropdown-item {{ active_class(Route::is('frontend.company.jobs')) }}">My
                                            Posted Jobs</a></li>
                                @endif
                                <li><a href="{{ route('frontend.user.account') }}"
                                        class="dropdown-item {{ active_class(Route::is('frontend.user.account')) }}">My
                                        Account Profile</a></li>
                                <li><a href="{{ route('frontend.auth.logout') }}"
                                        class="dropdown-item">@lang('navs.general.logout')</a></li>
                </div>
                </li>
            @endguest
            </ul>

        </div>
        <!-- /.navbar-collapse -->
        </div>
    </nav>
    <!-- ======================= End Navigation ===================== -->

    <!-- ======================= Start Page Title ===================== -->
    <div class="page-title light" style="background:url(/theme/img/banner-4.jpg);">
        <div class="container">
            <div class="col-sm-7">
                <div class="page-caption">
                    <h2>WorkProfile</h2>
                    {{-- <p><a href="#" title="Home">Home</a> <i class="ti-arrow-right"></i> Resume Detail</p> --}}
                    <p>{{ $user->full_name }}</p>
                </div>
            </div>
            <div class="col-sm-5 text-right mrg-top-30">
                <a href="/getCandidateResume/{{ $wp['cvLink'] }}" target="_blank"
                    class="btn btn-m btn-success">Download Resume</a>
            </div>
        </div>
    </div>
    <!-- ======================= End Page Title ===================== -->


    <!-- ====================== Resume Detail ================ -->
    <section class="gray">
        <div class="container">
            <!-- row -->
            <div class="row">

                <div class="col-md-8 col-sm-12">

                    <div class="detail-wrapper">
                        <div class="detail-wrapper-body">

                            <div class="text-center mrg-bot-30">
                                {{-- http://via.placeholder.com/400x400 --}}
                                <img src="{{ $user->avatar_location != null ? url('storage/' . $user->avatar_location) : 'https://www.gravatar.com/avatar/fa645c52bda684621480070cf6ba54dd.jpg?s=80&d=mm&r=g' }}"
                                    class="img-circle width-100" alt="" />
                                <h4 class="meg-0">{{ $user->full_name }}</h4>
                                <span>Candidate</span>
                            </div>

                            <div class="row">
                                <div class="col-sm-4 mrg-bot-10">
                                    <i class="ti-location-pin padd-r-10"></i>{{ $user->location }}
                                </div>
                                <div class="col-sm-4 mrg-bot-10">
                                    <i class="ti-email padd-r-10"></i>{{ $user->email }}
                                </div>
                                <div class="col-sm-4 mrg-bot-10">
                                    <i class="ti-mobile padd-r-10"></i>{{ $user->contact }}
                                </div>

                                {{-- <div class="col-sm-4 mrg-bot-10">
                                        <i class="ti-shield padd-r-10"></i>3 Year Exp.
                                    </div> --}}
                                @if (count($wp->skills) > 0)
                                    {{-- <tr><td class="text-center" colspan="3">Please add one or more skills!</td></tr> --}}
                                    <div class="col-sm-12 mrg-bot-10">
                                        <label>Skills: </label>
                                        @foreach ($wp->skills as $skill)
                                            <span class="skill-tag">{{ $skill['title'] }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Summary</h4>
                        </div>
                        <div class="detail-wrapper-body">
                            {{ $wp->summary }}
                        </div>
                    </div>
                    @php $colorClasses = ['info', 'success', 'danger', 'warning', 'primary']; @endphp
                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Education</h4>
                        </div>
                        <div class="detail-wrapper-body">

                            @foreach ($wp->educations as $education)
                                <div class="edu-history {{ $colorClasses[rand(0, 4)] }}">
                                    <i></i>
                                    <div class="detail-info">
                                        <h3>{{ $education['title'] }}</h3>
                                        <i>{{ $education['remarks'] }}</i>
                                        <span>{{ $education['shortDesc'] }}</span>
                                        <p>{{ $education['longDesc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Work & Experiences</h4>
                        </div>
                        <div class="detail-wrapper-body">

                            @foreach ($wp->experiences as $experience)
                                <div class="edu-history {{ $colorClasses[rand(0, 4)] }}">
                                    <i></i>
                                    <div class="detail-info">
                                        <h3>{{ $experience['title'] }}</h3>
                                        <i>{{ $experience['remarks'] }}</i>
                                        <span>{{ $experience['shortDesc'] }}</span>
                                        <p>{{ $experience['longDesc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="detail-wrapper">
                        <div class="detail-wrapper-header">
                            <h4>Certifications</h4>
                        </div>
                        <div class="detail-wrapper-body">

                            @foreach ($wp->certifications as $certification)
                                <div class="edu-history {{ $colorClasses[rand(0, 4)] }}">
                                    <i></i>
                                    <div class="detail-info">
                                        <h3>{{ $certification['title'] }}</h3>
                                        <i>{{ $certification['remarks'] }}</i>
                                        <span>{{ $certification['shortDesc'] }}</span>
                                        <p>{{ $certification['longDesc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="col-md-4 col-sm-12">
                    <div class="sidebar">

                        <!-- Start: Opening hour -->
                        <div class="widget-boxed lg">
                            <div class="widget-boxed-body">
                                {{-- <a href="#" class="btn btn-m theme-btn full-width mrg-bot-10"><i class="ti-heart"></i>Bookmark This</a> --}}
                                <a href="#" class="btn btn-m theme-btn full-width"><i
                                        class="ti-check"></i>Shortlist Candidate</a>
                            </div>
                        </div>
                        <!-- End: Opening hour -->

                        <!-- Start: Job Overview -->
                        <div class="widget-boxed">
                            <div class="widget-boxed-header">
                                <h4><i class="fa fa-info-circle"></i>&nbsp;Details</h4>
                            </div>
                            <div class="widget-boxed-body">
                                <div class="side-list no-border">
                                    <ul>
                                        <li><i class="ti-credit-card padd-r-10"></i>Work-Authorization:
                                            {{ $user->work_authorization }}</li>
                                        {{-- <li><i class="ti-world padd-r-10"></i></li> --}}
                                        <li><i class="ti-mobile padd-r-10"></i>{{ $user->contact }}</li>
                                        <li><i class="ti-email padd-r-10"></i>{{ $user->email }}</li>
                                        {{-- <li><i class="ti-pencil-alt padd-r-10"></i>Bachelor Degree</li> --}}
                                        {{-- <li><i class="ti-shield padd-r-10"></i>3 Year Exp.</li> --}}
                                    </ul>
                                    <h5>Social</h5>
                                    <ul class="side-list-inline no-border social-side">
                                        {{-- <li><a href="#"><i class="fa fa-facebook theme-cl"></i></a></li> --}}
                                        {{-- <li><a href="#"><i class="fa fa-google-plus theme-cl"></i></a></li> --}}
                                        {{-- <li><a href="#"><i class="fa fa-twitter theme-cl"></i></a></li> --}}
                                        <li><a href="#"><i class="fa fa-linkedin theme-cl"></i></a></li>
                                        {{-- <li><a href="#"><i class="fa fa-pinterest theme-cl"></i></a></li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End: Job Overview -->

                        <!-- Start: Opening hour -->
                        {{-- <div class="widget-boxed">
                                <div class="widget-boxed-header">
                                    <h4><i class="ti-headphone padd-r-10"></i>Contact Now</h4>
                                </div>
                                <div class="widget-boxed-body">
                                    <form>
                                        <input type="text" class="form-control" placeholder="Enter your Name *">
                                        <input type="text" class="form-control" placeholder="Email Address*">
                                        <input type="text" class="form-control" placeholder="Phone Number">
                                        <textarea class="form-control height-140" placeholder="Message should have more than 50 characters"></textarea>
                                        <button class="btn theme-btn full-width">Send Email</button>
                                        <span>You accepts our <a href="#" title="">Terms and Conditions</a></span>
                                    </form>
                                </div>
                            </div> --}}
                        <!-- End: Opening hour -->

                    </div>

                </div>
                <!-- End Sidebar -->

            </div>
            <!-- End Row -->
        </div>
    </section>

    <!-- ====================== End Resume Detail ================ -->


    <!-- ================= footer start ========================= -->
    <footer class="dark-bg footer">
        <div class="container">
            <!-- Row Start -->

            {{-- <div class="row"> --}}

            {{-- <div class="col-md-8 col-sm-8">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <h4>Terms of Service</h4>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <h4>Privacy Policy</h4>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <h4>Evaluating Partners</h4>
                            </div>
                        </div>
                    </div> --}}

            {{-- <div class="col-md-4 col-sm-4">
                        <h4>Featured Job</h4>
                        <!-- Newsletter -->
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter Email">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-location-arrow font-20"></i></button>
                            </span>
                        </div>
                        
                        <!-- Social Box -->
                        <div class="f-social-box">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook facebook-cl"></i></a></li>
                                <li><a href="#"><i class="fa fa-google google-plus-cl"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter twitter-cl"></i></a></li>
                                <li><a href="#"><i class="fa fa-pinterest pinterest-cl"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram instagram-cl"></i></a></li>
                            </ul>
                        </div>
                        
                        <!-- App Links -->
                        <div class="f-app-box">
                            <ul>
                                <li><a href="#"><i class="fa fa-apple"></i>App Store</a></li>
                                <li><a href="#"><i class="fa fa-android"></i>Play Store</a></li>
                            </ul>
                        </div>
                        
                    </div> --}}

            {{-- </div> --}}

            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <h4>Terms of Service</h4>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <h4>Privacy Policy</h4>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <h4>Evaluating Partners</h4>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <h4>Contact Us</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="copyriht text-center">
                        <p>&copy; Copyright 2020 <a href="truetalent.io">{{ env('APP_NAME') }}</a> | All Right
                            Reserved.</p>
                    </div>
                </div>
            </div>

        </div>
    </footer>

    <!-- Sign Up Window Code -->
    <div class="modal fade" id="signin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="myModalLabel1">
                <div class="modal-body">
                    <div class="text-center"><img src="/theme/img/logo.png" class="img-responsive" alt="">
                    </div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-advance theme-bg" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link" data-toggle="tab" href="#employer" role="tab">
                                <i class="ti-user"></i> Employer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#candidate" role="tab">
                                <i class="ti-user"></i> Candidate</a>
                        </li>
                    </ul>
                    <!-- Nav tabs -->

                    <!-- Tab panels -->
                    <div class="tab-content">

                        <!-- Employer Panel 1-->
                        <div class="tab-pane fade in show active" id="employer" role="tabpanel">
                            <form>

                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" class="form-control" placeholder="User Name">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" placeholder="*********">
                                </div>

                                <div class="form-group">
                                    <span class="custom-checkbox">
                                        <input type="checkbox" id="4">
                                        <label for="4"></label>Remember me
                                    </span>
                                    <a href="#" title="Forget" class="fl-right">Forgot Password?</a>
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" class="btn theme-btn full-width btn-m">LogIn </button>
                                </div>

                            </form>

                            <div class="log-option"><span>OR</span></div>

                            <div class="row mrg-bot-20">
                                <div class="col-md-6">
                                    <a href="#" title="" class="fb-log-btn log-btn"><i
                                            class="fa fa-facebook"></i>Sign In With Facebook</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#" title="" class="gplus-log-btn log-btn"><i
                                            class="fa fa-google-plus"></i>Sign In With Google+</a>
                                </div>
                            </div>

                        </div>
                        <!--/.Panel 1-->

                        <!-- Candidate Panel 2-->
                        <div class="tab-pane fade" id="candidate" role="tabpanel">
                            <form>

                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" class="form-control" placeholder="User Name">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" placeholder="*********">
                                </div>

                                <div class="form-group">
                                    <span class="custom-checkbox">
                                        <input type="checkbox" id="4">
                                        <label for="4"></label>Remember me
                                    </span>
                                    <a href="#" title="Forget" class="fl-right">Forgot Password?</a>
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" class="btn theme-btn full-width btn-m">LogIn </button>
                                </div>

                            </form>

                            <div class="log-option"><span>OR</span></div>

                            <div class="row mrg-bot-20">
                                <div class="col-md-6">
                                    <a href="#" title="" class="fb-log-btn log-btn"><i
                                            class="fa fa-facebook"></i>Sign In With Facebook</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#" title="" class="gplus-log-btn log-btn"><i
                                            class="fa fa-google-plus"></i>Sign In With Google+</a>
                                </div>
                            </div>

                        </div>
                        <!--/.Panel 2-->

                    </div>
                    <!-- Tab panels -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Sign Up Window -->

    <!-- =================== START JAVASCRIPT ================== -->
    <!-- Jquery js-->
    {{-- <script src="/theme/js/jquery.min.js"></script> --}}

    <!-- Bootstrap js-->
    {{-- <script src="/theme/plugins/bootstrap/js/bootstrap.min.js"></script> --}}

    <!-- Bootsnav js-->
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/frontend.js')) !!}
    {!! script(mix('js/themes.js')) !!}
    <script type="text/javascript">
        $(function() {
            $('.comingSoon').on('click', function() {
                toastr.info('Coming Soon');
            });
        });
    </script>
    {{-- <script src="/theme/plugins/bootstrap/js/bootsnav.js"></script> --}}
    {{-- <script src="/theme/js/viewportchecker.js"></script> --}}

    <!-- Slick Slider js-->
    {{-- <script src="/theme/plugins/slick-slider/slick.js"></script> --}}

    <!-- wysihtml5 editor js -->
    {{-- <script src="/theme/plugins/bootstrap/js/wysihtml5-0.3.0.js"></script> --}}
    {{-- <script src="/theme/plugins/bootstrap/js/bootstrap-wysihtml5.js"></script> --}}

    <!-- Nice Select -->
    {{-- <script src="/theme/plugins/nice-select/js/jquery.nice-select.min.js"></script> --}}

    <!-- Custom Js -->
    {{-- <script src="/theme/js/custom.js"></script> --}}

</body>

</html>
