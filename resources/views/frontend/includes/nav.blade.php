<nav class="navbar navbar-expand-lg navbar-light navbar-main" style="position: sticky;z-index:99;top:1px;">
    <a href="{{ route('frontend.index') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40"
            alt="{{ env('APP_NAME') }} Logo" style="max-width: 140px;">
        {{-- <h4 class="ml-3">@yield('page_title')</h4> --}}
        <!-- <span style="font-family: 'nunito'; font-size: 1.6rem; vertical-align: middle;">
            <span class="colorBlue">True</span>
            <span class="colorRed">Talent</span>
            <span class="colorBlue">.</span>
            <span class="colorRed" style="margin-left:1px;">io</span>
        </span> -->
    </a>

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="@lang('labels.general.toggle_navigation')">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav navMain">
            @if (config('locale.status') && count(config('locale.languages')) > 1)
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('menus.language-picker.language')
                        ({{ strtoupper(app()->getLocale()) }})</a>

                    @include('includes.partials.lang')
                </li>
            @endif

            {{-- @auth --}}
            {{-- <li class="nav-item"><a href="javascript:void(0);" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">@lang('navs.frontend.dashboard')</a></li> --}}
            {{-- @endauth --}}
            @auth
                @can('view backend')
                    <li class="nav-item "><a href="{{ route('admin.dashboard') }}" class="nav-link">@lang('navs.frontend.user.administration')</a></li>
                @endcan

                {{-- Candidate Navs --}}
                @if ($logged_in_user->hasRole('candidate'))
                    <li class="nav-item "><a href="{{ route('frontend.candidate.myJobs') }}"
                            class="nav-link {{ active_class(Route::is('frontend.candidate.myJobs')) }}">My Jobs</a></li>
                    <li class="nav-item "><a href="{{ route('frontend.user.work_profile') }}"
                            class="nav-link {{ active_class(Route::is('frontend.user.work_profile')) }} {{ active_class(Route::is('frontend.candidate.personalProfile')) }}">My
                            Work Profile</a></li>
                @endif

                {{-- Corporate Navs --}}
                @if ($logged_in_user->hasRole('company admin') || $logged_in_user->hasRole('company user'))
                    <li class="nav-item "><a href="{{ route('frontend.company.jobs') }}"
                            class="nav-link {{ active_class(Route::is('frontend.company.jobs')) }}">My Jobs</a></li>
                @endif
                @if ($logged_in_user->hasRole('company admin'))
                    <li class="nav-item "><a href="{{ route('frontend.company.companyUsers') }}"
                            class="nav-link {{ active_class(Route::is('frontend.company.companyUsers')) }}">Admin</a></li>
                @endif

            @endauth

            @guest
                <li class="nav-item "><a href="{{ route('frontend.auth.login') }}"
                        class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}">@lang('navs.frontend.login')</a></li>
            @else
                <li class="nav-item  dropdown mr-4">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->name }}</a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuUser">
                        <a href="{{ route('frontend.user.account') }}"
                            class="dropdown-item {{ active_class(Route::is('frontend.user.account')) }}">@lang('navs.frontend.user.change_password')</a>
                        <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">@lang('navs.general.logout')</a>
                    </div>
                </li>
            @endguest

            {{-- <li class="nav-item"><a href="{{route('frontend.contact')}}" class="nav-link {{ active_class(Route::is('frontend.contact')) }}">@lang('navs.frontend.contact')</a></li> --}}
        </ul>
        {{-- {{Route::is('frontend.user.work_profile')}} --}}
    </div>
</nav>
