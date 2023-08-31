<style type="text/css">
    .breadcrumb {
        min-width: 172%;
        font-size: 10px;
    }
    .navbar {
      width: 100% !important;
      right: 100% !important;
      left: 0% !important;
  }
</style>
<nav class="navbar">
      <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
      </a>
  <div class="navbar-content">
    <a href="#" class="">
        <div class="mt-3">
          <a href="/" class="noble-ui-logo d-block mb-2"><img src="{{ asset('img/logo.png') }}" alt="{{ env('APP_NAME') }} Logo" class="mx-2"
            style="max-width: 140px;height: auto;"></a>
        </div>
    </a>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
          <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
            <div class="mb-3">
              <img class="wd-80 ht-80 rounded-circle" src="" alt="">
            </div>
            <div class="text-center">
              <p class="tx-16 fw-bolder"></p>
            </div>
          </div>
        </div>
          @guest
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="wd-30 ht-30 rounded-circle" src="https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028.jpg?s=80&d=mm&r=g" alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                  <ul class="list-unstyled p-1">
                    <li class="dropdown-item py-2">
                      <a href="{{ route('frontend.auth.login') }}" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="log-in"></i>
                        <span> Login</span>
                      </a>
                    </li>
                  </ul>
                  
                </div>
              </li>
          @else
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="wd-30 ht-30 rounded-circle" src="{{ $logged_in_user->picture }}" alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                  <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                    <div class="mb-3">
                      <img class="wd-80 ht-80 rounded-circle" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}">
                    </div>
                    <div class="text-center">
                      <p class="tx-16 fw-bolder">{{ $logged_in_user->full_name }}</p>
                    </div>
                  </div>
                  <ul class="list-unstyled p-1">
                    <li class="dropdown-item py-2">
                      <a href="{{ route('frontend.user.account') }}" class="text-body ms-0 {{ active_class(Route::is('frontend.user.account')) }}">
                        <i class="me-2 icon-md" data-feather="repeat"></i>
                        <span>@lang('navs.frontend.user.change_password')</span>
                      </a>
                    </li>
                    <li class="dropdown-item py-2">
                      <a href="{{ route('frontend.auth.logout') }}" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="log-out"></i>
                        <span> @lang('navs.general.logout')</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
          @endguest
    </ul>
  </div>
</nav>