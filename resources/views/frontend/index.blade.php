@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | ' . __('navs.general.home'))

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
    .fancy{
        font-family: 'Pacifico', cursive;
    }

    .main-search{
        background-image: url("{{asset('img/frontend/Work1.jpg')}}");
        background-size: cover;
        background-position: center;
        color: #fff;
    }
    .main-search .inner{
        min-height: 30vh;
        background-color: rgba(49, 49, 49, 0.4)
    }
    .select2-container--default .select2-search--inline .select2-search__field{
        width: 150%!important;
    }
    #separator{
        position: absolute;
        right: 25%;
        border-right: 1px solid #fff; 
        width: 0px;
        min-height: 20vh;
        top: 10rem;
    }
    .switch { 
        position : relative ;
        display : inline-block;
        width : 60px;
        height : 30px;
        background-color: #eee;
        border-radius: 40px;
    }
    .switch::after {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        top: 2px;
        left: 1px;
        transition: all 0.4s;
    }
    .checkbox:checked + .switch::after {
        left : 30px; 
    }
    .checkbox:checked + .switch {
        background-color: #7983ff;
    }
    .checkbox { 
        display : none;
    }
    /* .select2-container--default .select2-selection--multiple{ */
        /* padding: 2%; */
    }
    /* .select2-container--default .select2-selection--multiple .select2-selection__choice{ */
        /* color: #1e1e1e; */
    /* } */
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link{
        color: #fff;
        border-bottom: 1px solid #fff;
        background-color: #00000000!important;
    }
    .nav-pills .nav-link{
        color: #040404a8;
    }
    .searchNav{
        font-size: 1.2rem;
    }
    .separator{
        border-left: 0.5px solid #cecece;
        height: 40px;
        /* box-shadow: 0px 0px 2px 0px #8c8c8c; */
    }
    @media (min-width: 768px)
    @media (min-width: 992px){
        .leftLabelText10, .leftLabelText15{
            max-width: 100%!important;
        }
    }
    
    @media (min-width: 1200px){
    .leftLabelText10{
        max-width: 10%!important;
    }
    .leftLabelText15{
        max-width: 16%!important;
    }
    }
    .advSearchDiv{
        max-width: 13%!important;
    }
    @media (max-width: 576px), (max-width: 768px){
        .findJobs, .advSearchDiv{
            max-width: 100%!important;
        }
    }
    @media (min-width: 1200px){
    .col-xl-1 {
        flex: 0 0 10.333333%!important;
        max-width: 10.333333%!important;
    }}

    .visi-none{
        visibility: none;
    }
</style>
@section('content')
    {{-- <div class="row"> --}}
        {{-- <div class="p-4 inner"> --}}
        {{-- <h2>@lang('strings.frontend.search', ['type' => 'Jobs'])</h2> --}}
        {{-- <div class="p-3 mt-1"> --}}
            {{-- <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item searchNav">
                    <a class="nav-link active" id="jobs-tab" href="#searchJobs" data-toggle="tab" role="tab" aria-controls="searchJobs" aria-selected="true"><i class="fas fa-briefcase"></i> Search Jobs</a>
                </li>
                <li class="nav-item searchNav">
                    <a class="nav-link" id="talents-tab" href="#searchJob" data-toggle="tab" role="tab" aria-controls="searchTalents" aria-selected="true"><i class="fas fa-users"></i> Search Talents</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent"> --}}
                {{-- <div class="tab-pane fade show active" id="searchJobs" role="tabpanel" aria-labelledby="jobs-tab"> --}}
        {{-- {{ route('frontend.searchJobs') }} --}}
        
                {{-- </div> --}}
                {{-- <div class="tab-pane fade" id="searchTalents" role="tabpanel" aria-labelledby="talents-tab"> --}}
                    {{-- <h2>kjbjkbkj</h2> --}}
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div><!--row--> --}}

    <div class="row justify-content-center align-items-center mtPanel">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 align-self-center px-0">
            {{-- @if(auth()->guest()) --}}
            <div id="carouselMain" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselMain" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselMain" data-slide-to="1"></li>
                    <li data-target="#carouselMain" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                     <div class="carousel-item active">
                        <!--class="bd-placeholder-img bd-placeholder-img-lg d-block w-100"-->
                        <img class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" src="{{asset('img/frontend/Main-Banner-1.jpg')}}"></img>
                        <!--<svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Second slide"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">First slide</text></svg>-->
                    </div>
                    <div class="carousel-item">
                        <img class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" src="{{asset('img/frontend/Main-Banner-1.jpg')}}"></img>
                        <!--<svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Second slide"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>-->
                    </div>
                    <div class="carousel-item">
                        <img class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" src="{{asset('img/frontend/Main-Banner-1.jpg')}}"></img>
                        <!--<svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Second slide"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>-->
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselMain" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselMain" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            {{-- @else --}}
            {{-- <center><div class="mx-auto mt-3"><img src="{{asset('img/frontend/Job-progress.png')}}" width="170" /></div></center> --}}
            {{-- <h2 class="display-4 text-center pt-3">Your <span class="fancy" style="color: #00327d;">Dream</span> job at best locations is just a <span class="fancy" style="color: #ff7e2a;">click</span> away!</h2> --}}
            {{-- <h3 class="display-4 mt-5 text-center">Enter your <span class="fancy">Skills</span> to get started!</h3> --}}
            {{-- @endif --}}
        </div>
    </div>
    <div class="row my-5 py-3">
        @for($i = 0; $i <= 7; $i++)   
            <div class="col-4 col-sm-4 col-md-1 col-lg-1 col-xl-1">
                @if($i%2 == 0)
                    <img src="{{ asset('/img/logo.png') }}" width="60" class="rounded mx-auto d-block p-2" alt="{{ env('APP_NAME') }}">
                @else
                    <img src="{{ asset('/img/frontend/appringer.png') }}" width="60" class="rounded mx-auto d-block p-2" alt="{{ env('APP_NAME') }}">
                @endif
            </div>
        @endfor
    </div>
    {{-- <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fab fa-font-awesome-flag"></i> Font Awesome @lang('strings.frontend.test')
                </div>
                <div class="card-body">
                    <i class="fas fa-home"></i>
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-pinterest"></i>
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row--> --}}
@endsection
@push('after-scripts')
    <script type="text/javascript">
        $(function(){
            $('.updated_atGrp').flatpickr({wrap: true, maxDate: 'today', disableMobile: "true",});
            $('#resetSearch').on('click', function(){
                document.getElementById('searchForm').reset();
                var inputs = $('#searchForm').find('input');
                $.each(inputs, function(i, v){
                    if($(v).attr('name') == '_token') return false;
                    $(v).val('').removeAttr('checked');
                });
                toastr.info('All Search parameters have been reset!');
            });

            $('.advSearchBtn').on('click', function(){
                $('.arrowUp').toggleClass('d-none');
                $('.arrowDown').toggleClass('d-none');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('select#skills, select#seekerSkills').select2({
                placeholder: 'Try typing a Skill(s)...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: false,
                selectOnClose: true,
                scrollAfterSelect: true,
                width: '100%',
                multiple: true,
                tags: true,
                ajax: {
                    url: "{{ route('frontend.getSkills') }}",
                    type: "post",
                    dataType: 'json'
                }
            }).on('select2:select', function(){
                $('.select2-search__field').val('');
            });

            $('select#locations, select#seekerLocations').select2({
                placeholder: 'Try typing a city, state or zip...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: false,
                selectOnClose: true,
                scrollAfterSelect: true,
                multiple: true,
                width: '100%',
                // tags: true,
                ajax: {
                    url: "{{ route('frontend.getLocations') }}",
                    type: "post",
                    dataType: 'json'
                }
            }).on('select2:select', function(){
                $('.select2-search__field').val('');
            });

            $('#travel').on('change', function(e){
                if(!$(this).is(":checked")) $('input[name="percentage"]').val(0);
            });

            $('select#work_authorization').select2({
                placeholder: 'Select Work Authorization',
                multiple: true,
                width: '100%',
                closeOnSelect: false
            }).val(null).trigger('change');

            $('#advancedSearch').on('show.bs.collapse hide.bs.collapse', function() {
                $('.btnp').toggleClass('d-none');
            });

            const ua = window.navigator.userAgent;
            const msie = ua.indexOf("MSIE ");
            // console.log(ua, msie);
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){ // If Internet Explorer, return version number
                toastr.warning('To get best experince, please use Google Chrome, Mozilla FireFox or Safari browser!');
                // alert(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
            }

            let minExp = $('input[name=min-experience]').val(); let maxExp = $('input[name=max-experience]').val();
            function getExp(){
                minExp = parseInt($('input[name=min-experience]').val());
                maxExp = parseInt($('input[name=max-experience]').val());
            }

            $('input[name=min-experience]').on('change', function(){
                getExp();
                if(minExp > maxExp && maxExp != NaN){ $('input[name=min-experience]').val(maxExp); }
            });
            $('input[name=max-experience]').on('change', function(){
                getExp();
                if(minExp > maxExp && minExp != NaN){ $('input[name=max-experience]').val(minExp); }
            });

            $('.findJobsBtn').on('click', function(){
                $('form#searchForm').attr('action', '/search');
                $('.jobsSeparator').addClass('d-none');
                $('.seekerSeparator').removeClass('d-none');
                $('.tPRow').addClass('d-none');
                $('.mtPanel').css('margin-top', '70px');
                window.setTimeout(()=>{
                    $('.mtPanel').css('margin-top', '0');
                    $('.tPRow').removeClass('d-none');
                }, 100)
                $('.seekerSearchDiv').addClass('d-none');
                $('.jobSearchDiv').removeClass('d-none');
                // $('.seekerSearchDiv').removeClass('fadeInRightBig').addClass('fadeOutRightBig d-none');
                // $('.jobSearchDiv').removeClass('d-none fadeOutRightBig').addClass('fadeInRightBig');
                // $('.searchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
                // $('.advSearchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
            });

            // Job Seeker Search
            $('.findJobSeekersBtn').on('click', function(){
                $('form#searchForm').attr('action', '/searchCandidates');
                $('.jobsSeparator').removeClass('d-none');
                $('.seekerSeparator').addClass('d-none');
                $('.jobSearchDiv').addClass('d-none');
                $('.seekerSearchDiv').removeClass('d-none');
                $('.tPRow').addClass('d-none');
                $('.mtPanel').css('margin-top', '70px');
                window.setTimeout(()=>{
                    $('.mtPanel').css('margin-top', '0');
                    $('.tPRow').removeClass('d-none');
                }, 100)
                // $('.tPRow').removeClass('fadeInRightBig').addClass('fadeInRightBig');
                // $('.jobSearchDiv').removeClass('fadeInRightBig').addClass('fadeOutRightBig d-none');
                // $('.seekerSearchDiv').removeClass('d-none fadeOutRightBig').addClass('fadeInRightBig');
                // $('.searchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
                // $('.advSearchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
            });

            @if(!auth()->guest() && (auth()->user()->isCompanyAdmin() || auth()->user()->isCompanyUser()))
                $('form#searchForm').attr('action', '/searchCandidates');
                $('.jobsSeparator').removeClass('d-none');
                $('.seekerSeparator').addClass('d-none');
                $('.jobSearchDiv').addClass('d-none');
                $('.seekerSearchDiv').removeClass('d-none');
                $('.jobLeft, .seekerRight').addClass('d-none');
                $('.jobRight, .seekerLeft').removeClass('d-none');
                // $('.searchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
                // $('.advSearchBtnMain').removeClass('fadeInRightBig').addClass('fadeInRightBig');
            @endif

            $('.jobRight').on('click', function(){
                $('.leftLabelText15').removeClass('leftLabelText15').addClass('leftLabelText10');
                $('.jobRight').addClass('d-none');
                $('.jobLeft').removeClass('d-none');
                $('.seekerRight').removeClass('d-none');
                $('.seekerLeft').addClass('d-none');
            });

            $('.seekerRight').on('click', function(){
                $('.leftLabelText10').removeClass('leftLabelText10').addClass('leftLabelText15');
                $('.jobRight').removeClass('d-none');
                $('.jobLeft').addClass('d-none');
                $('.seekerRight').addClass('d-none');
                $('.seekerLeft').removeClass('d-none');
            });
        });
    </script>
@endpush
