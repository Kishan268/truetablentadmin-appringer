<style type="text/css">
  .sidebar .sidebar-body .nav.sub-menu .nav-item .nav-link.active.users {
/*     color: black !important; */
}
</style>
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="{{ env('APP_NAME') }} Logo" class="mx-2"
                style="max-width: 140px;height: auto;">
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            @canany('view_company_dashboard', 'view_candidate_dashboard', 'view_jobs_gigs_dashboard')
                <li class="nav-item {{ active_class(['/']) }}">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ active_class(Route::is('admin/dashboard')) }}">
                        <!-- <i class="link-icon" data-feather="box"></i> -->
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/dashbord.svg') }}" alt="" />
                        <span class="link-title">@lang('menus.backend.sidebar.dashboard')</span>
                    </a>
                </li>
            @endcanany
            @canany('view_company', 'add_company', 'update_company', 'delete_company')
                <li class="nav-item {{ active_class(Route::is('admin/auth/allcompany')) }}">
                    <a href="{{ route('admin.auth.allcompany.index') }}"
                        class="nav-link {{ active_class(Route::is('admin/auth/allcompany')) }} ">
                        <img class="link-icon" src="{{ asset('sidebar-icons//new/buildings.svg') }}" alt="" />
                        <span class="link-title">Companies</span>
                    </a>
                </li>
            @endcanany
            @canany('view_job', 'add_job', 'update_job', 'delete_job', 'view_reported_job')
                <li class="nav-item ">
                    <a class="nav-link" data-bs-toggle="collapse" href="#jobs" role="button"
                        aria-expanded="{{ is_active_route(['alljobs/*']) }}" aria-controls="jobs">
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/job.svg') }}" alt="" />
                        <span class="link-title">Jobs</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['alljobs/*']) }}" id="jobs">
                        <ul class="nav sub-menu">
                            @canany('view_job', 'add_job', 'update_job', 'delete_job')
                                <li class="nav-item ">
                                    <a href="{{ route('admin.auth.company.alljobs') }}"
                                        class="nav-link {{ active_class(Route::is('admin/auth/alljobs')) }} ">Jobs</a>
                                </li>
                            @endcanany
                            @can('view_reported_job')
                                <li class="nav-item {{ request()->is('admin/auth/company/jobs/reported') ? 'active' : '' }}">
                                    <a href="{{ route('admin.auth.company.jobs.reported') }}"
                                        class="nav-link reported ">Reported Jobs</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany('view_gig', 'add_gig', 'update_gig', 'delete_gig', 'view_reported_gig')
                <li class="nav-item {{ active_class(Route::is('admin/auth/gigs')) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#allgigs" role="button"
                        aria-expanded="{{ is_active_route(['allgigs/*']) }}" aria-controls="allgigs">
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/gigs.svg') }}" alt="" />
                        <span class="link-title">Gigs</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>
                    <div class="collapse {{ show_class(['allgigs/*']) }}" id="allgigs">
                        <ul class="nav sub-menu">
                            @canany('view_gig', 'add_gig', 'update_gig', 'delete_gig')
                                <li class="nav-item ">
                                    <a href="{{ route('admin.auth.gigs.allgigs') }}"
                                        class="nav-link {{ active_class(Route::is('admin/auth/allgigs')) }} ">Gigs</a>
                                </li>
                            @endcanany
                            @can('view_reported_gig')
                                <li class="nav-item {{ request()->is('admin/auth/gigs/all-reported-gigs') ? 'active' : '' }}">
                                    <a href="{{ route('admin.auth.gigs.all-reported-gigs') }}"
                                        class="nav-link reported ">Reported Gigs</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany('view_user', 'add_user', 'update_user', 'delete_user')
                <li class="nav-item {{ active_class(Route::is('admin/auth/user')) }}">
                    <a class="nav-link {{ active_class(Route::is('admin/auth/user')) }}" data-bs-toggle="collapse"
                        href="#users" role="button" aria-expanded="" aria-controls="users">
                        {{-- <i class="link-icon" data-feather="users"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/users.svg') }}" alt="" />
                        <span class="link-title">Users</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>
                    <div class="collapse" id="users">
                        @php
                            $roles = App\Models\Auth\Role::all();
                            $array = [];
                        @endphp

                        <ul class="nav sub-menu">
                            @foreach ($roles as $role)
                              @php
                                $array[] =  str_replace(' ','',$role->name);
                              @endphp
                                <li class="nav-item">
                                    <a href="{{ route('admin.auth.user.index', ['type' => $role->name]) }}"
                                        class="nav-link users {{str_replace(' ','',$role->name)}}" id="{{ $role->name }}">{{ ucwords($role->name) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endcanany
            <li class="nav-item nav-category">@lang('menus.backend.sidebar.system') Management</li>

            @canany('view_referral', 'add_referral', 'update_referral')
                <li class="nav-item {{ active_class(Route::is('admin/auth/referrals')) }}">
                    <a href="{{ route('admin.auth.referrals.index') }}"
                        class="nav-link {{ active_class(Route::is('admin/auth/referrals')) }}">
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/refrence.svg') }}" alt="" />
                        <span class="link-title">Referrals</span>
                    </a>
                </li>
            @endcanany

            @canany('view_featured_logo', 'add_featured_logo', 'edit_featured_logo', 'delete_featured_logo')
                <li class="nav-item {{ active_class(Route::is('admin/auth/homepage-logos')) }}">
                    <a href="{{ route('admin.auth.homepage-logos.index') }}"
                        class="nav-link {{ active_class(Route::is('admin/auth/homepage-logos')) }}">

                        <img class="link-icon" src="{{ asset('sidebar-icons/new/featured-logo.svg') }}"
                            alt="" />
                        <span class="link-title">Featured Logos</span>
                    </a>
                </li>
            @endcanany
            @canany('view_featured_job', 'add_featured_job', 'edit_featured_job', 'delete_featured_job')
                <li class="nav-item">
                    <a href="{{ route('admin.auth.featured-jobs.index') }}"
                        class="nav-link {{ active_class(Route::is('admin/auth/featured-jobs')) }}">
                        {{-- <i class="link-icon" data-feather="message-square"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/featured-job.svg') }}" alt="" />
                        <span class="link-title">Featured Jobs</span>
                    </a>
                </li>
            @endcanany
            @canany('view_featured_gigs', 'add_featured_gigs', 'edit_featured_gigs', 'delete_featured_gigs')
                <li class="nav-item">
                    <a href="{{ route('admin.auth.featured-gigs.index') }}" class="nav-link ">
                        {{-- <i class="link-icon" data-feather="message-square"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/gigs.svg') }}" alt="" />
                        <span class="link-title">Featured Gigs</span>
                    </a>
                </li>
            @endcanany
            @canany('view_roles_and_permissions', 'add_roles_and_permissions', 'edit_roles_and_permissions',
                'delete_roles_and_permissions')
                <li class="nav-item {{ active_class(Route::is('admin/auth/permission*')) }}">
                    <a href="{{ route('admin.auth.permission.index') }}"
                        class="nav-link {{ active_class(Route::is('admin/auth/permission*')) }}">
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/role.svg') }}" alt="" />
                        <span class="link-title">Roles and Permissions</span>
                    </a>
                </li>
            @endcanany

           
            @canany('view_footer_content', 'add_footer_content')
                <li class="nav-item {{ active_class(Route::is('admin.footer_content')) }}">
                    <a href="{{ route('admin.footer_content') }}"
                        class="nav-link {{ active_class(Route::is('admin.footer_content')) }}">
                        {{-- <i class="link-icon" data-feather="dollar-sign"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/footer.svg') }}" alt="" />
                        <span class="link-title">Footer</span>
                    </a>
                </li>
            @endcanany

            @canany('view_popups','add_popups')
                <li class="nav-item {{ active_class(Route::is('admin.popup_management')) }}">
                    <a href="{{ route('admin.popup_management') }}"
                        class="nav-link {{ active_class(Route::is('admin.popup_management')) }}">
                        {{-- <i class="link-icon" data-feather="dollar-sign"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/popup.svg') }}" alt="" />
                        <span class="link-title">Popups</span>
                    </a>
                </li>
            @endcanany

            @canany('view_payment')
                <li class="nav-item {{ active_class(Route::is('admin.auth.company.payments')) }}">
                    <a href="{{ route('admin.auth.company.payments') }}"
                        class="nav-link {{ active_class(Route::is('admin.auth.company.payments')) }}">
                        {{-- <i class="link-icon" data-feather="dollar-sign"></i> --}}
                        <img class="link-icon" src="{{ asset('sidebar-icons/new/payment.svg') }}" alt="" />
                        <span class="link-title">Payments</span>
                    </a>
                </li>
            @endcanany
            <li class="nav-item {{ active_class(Route::is('admin.auth.notification.index')) }}">
                <a href="{{ route('admin.auth.notification.index') }}"
                    class="nav-link {{ active_class(Route::is('admin.auth.notification.index')) }}">
                    <img class="link-icon" src="{{ asset('sidebar-icons/new/notification.svg') }}" alt="" />
                    <span class="link-title">Notification System</span>
                </a>
            </li> 
        </ul>
    </div>
</nav>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<style type="text/css">
    .sidebar .sidebar-body .nav.sub-menu .nav-item .nav-link.active:before {
        color: black !important;
    }
</style>
<script type="text/javascript">
    var data = <?php echo json_encode($array); ?>;
    var satusUrl = "{{app('request')->input('type')}}";
    var url = satusUrl.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '')
    $.each( data, function( key, value ) {
        $("."+value).css('color','black');
        if(value === url){
          $("."+value).css('color','#14BC9A');
        }
    });
</script>
