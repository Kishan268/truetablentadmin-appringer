<style type="text/css">
    .breadcrumb {
        min-width: 172%;
        font-size: 10px;
    }
</style>
<nav class="navbar">
     <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
      </a>
  <div class="navbar-content">
    <a href="#" class="">
       {{--  <img src="{{ asset('img/logo.png') }}" alt="{{ env('APP_NAME') }} Logo" class="mx-2"
            style="max-width: 140px;height: auto;"> --}}
        <div class="mt-4">
          @php     
          $satusUrl = app('request')->input('type');
          @endphp

          @if(request()->segment(count(request()->segments())) === 'user' )
            <ol class="breadcrumb" style="font-size: 0.875em;">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item active">User</li>
                  <li class="breadcrumb-item active">{{ucfirst($satusUrl)}}</li>
            </ol>
          @elseif(request()->segment(count(request()->segments())) === 'edit' )
            <ol class="breadcrumb" style="font-size: 0.875em;">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item active">User</li>
                  <li class="breadcrumb-item active">{{ucfirst('Edit User')}}</li>
            </ol>
          @else
            {{Breadcrumbs::render()}}
          @endif
     </div>
    </a>
    <ul class="navbar-nav">

     {{--  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell"></i>
          <div class="indicator">
            <div class="circle"></div>
          </div>
        </a>
        <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
          <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
            <p>6 New Notifications</p>
            <a href="javascript:;" class="text-muted">Clear all</a>
          </div>
          <div class="p-1">
            <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <i class="icon-sm text-white" data-feather="gift"></i>
              </div>
              <div class="flex-grow-1 me-2">
                <p>New Order Recieved</p>
                <p class="tx-12 text-muted">30 min ago</p>
              </div>    
            </a>
            <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <i class="icon-sm text-white" data-feather="alert-circle"></i>
              </div>
              <div class="flex-grow-1 me-2">
                <p>Server Limit Reached!</p>
                <p class="tx-12 text-muted">1 hrs ago</p>
              </div>    
            </a>
            <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <img class="wd-30 ht-30 rounded-circle" src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
              </div>
              <div class="flex-grow-1 me-2">
                <p>New customer registered</p>
                <p class="tx-12 text-muted">2 sec ago</p>
              </div>    
            </a>
            <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <i class="icon-sm text-white" data-feather="layers"></i>
              </div>
              <div class="flex-grow-1 me-2">
                <p>Apps are ready for update</p>
                <p class="tx-12 text-muted">5 hrs ago</p>
              </div>    
            </a>
            <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
              <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                <i class="icon-sm text-white" data-feather="download"></i>
              </div>
              <div class="flex-grow-1 me-2">
                <p>Download completed</p>
                <p class="tx-12 text-muted">6 hrs ago</p>
              </div>    
            </a>
          </div>
          <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
            <a href="javascript:;">View all</a>
          </div>
        </div>
      </li> --}}
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
            {{-- <li class="dropdown-item py-2">
              <a href="{{ url('/general/profile') }}" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="user"></i>
                <span>Profile</span>
              </a>
            </li>
            <li class="dropdown-item py-2">
              <a href="javascript:;" class="text-body ms-0">
                <i class="me-2 icon-md" data-feather="edit"></i>
                <span>Edit Profile</span>
              </a>
            </li> --}}
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
    </ul>
  </div>
</nav>