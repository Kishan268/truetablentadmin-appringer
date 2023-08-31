@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@push('after-styles')

    <style>
        .stats {
            font-size: 4rem;
        }

        .redirectTo {
            cursor: pointer;
        }

        .title h4 {
            /*width: 111px;*/
            height: 28px;
            font-style: normal;
            font-weight: 600;
            font-size: 20px;
            line-height: 28px;
            color: #2E2C34;
        }

        .title p {
            width: 272px;
            height: 20px;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: #898989;
        }

        .description {
            margin-top: 10px;
            display: flex;
            gap: 6px;
        }

        .description img {
            height: 12px;
            margin-top: 3px;
        }

        .vector {
            background: #00A5FF;
            mix-blend-mode: normal;
            border-radius: 76px;
            height: 64px;
            width: 64px;
        }

        .vector img {
            position: absolute;
            left: 4%;
            top: 35.33%;
        }

        .company-count {
            display: flex;
            margin-top: 10px;
        }

        .tag-label {
            width: 60%;
            font-style: normal;
            font-weight: 700;
            font-size: 12px;
            color: #898989;
        }

        .tag-data {
            display: flex;
            gap: 5px;
        }

        .tag-data p {
            width: 35px;
            height: 20px;
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
            color: #2E2C34;
        }
        .indicator {
            height: 12px;
            width: 12px;
            border-radius: 2px;
            margin-top: 2px;
        }
        .indicator.up {
            background: #20C9AC;
        }
        .indicator.down {
            background: #FC3400;
        }
        .indicator img{
            height: 11px;
            width: 11px;
            margin-bottom: 76%;
        }
        .industry-div {
            gap: 15px;
            padding-left: 10%;
            margin-top: 12px;
        }
        .industry-div img {
            height: 23px;
            width: 23px;
            margin-top: 30%;
        }
        .date-card{
            width: 29%;
            float: right;
        }
        .date-body{
            padding: 0.5rem !important;
        }
        .horn-icon{
            width: 64px;
        }
        .indicator-icon{
            width: 16px;
            height: 16px;
        }
        .featured-title {
            color: #84818A;
            font-weight: 600;
            font-size: 12px;
        }
        .featured-count {
            color: #2E2C34;
            font-weight: 700;
            font-size: 24px;
        }
        .featured-card-body{
            padding: 1rem;
        }
        .right{
            float: right;
        }
        .center{
            align-items: center !important;
        }

#loading-wrapper {
  position: fixed;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
      z-index: 1000;
    background: #fff;
    display: none;
}
.spinner-border {
/*  position: fixed;*/
  color:#14BC9A;
/*  margin: -7px 0 0 -45px;*/
  text-align: center;
  font-family: 'PT Sans Narrow', sans-serif;
  font-size: 25px;
 /* width: 100%;
  height: 100%;
  left: 0;
  top: 0;
      z-index: 1000;
    background: #fff;
    display: none;*/
}

#loading-text {
  display: block;
  position: absolute;
  top: 50%;
  left: 50%;
  color: #999;
  width: 100px;
  height: 30px;
  margin: -7px 0 0 -45px;
  text-align: center;
  font-family: 'PT Sans Narrow', sans-serif;
  font-size: 20px;
}

#loading-content {
  display: block;
  position: relative;
  left: 50%;
  top: 50%;
  width: 170px;
  height: 170px;
  margin: -85px 0 0 -85px;
  border: 3px solid #F00;
}

#loading-content:after {
  content: "";
  position: absolute;
  border: 3px solid #0F0;
  left: 15px;
  right: 15px;
  top: 15px;
  bottom: 15px;
}

#loading-content:before {
  content: "";
  position: absolute;
  border: 3px solid #00F;
  left: 5px;
  right: 5px;
  top: 5px;
  bottom: 5px;
}

#loading-content {
  border: 3px solid transparent;
  border-top-color: #4D658D;
  border-bottom-color: #4D658D;
  border-radius: 50%;
  -webkit-animation: loader 2s linear infinite;
  -moz-animation: loader 2s linear infinite;
  -o-animation: loader 2s linear infinite;
  animation: loader 2s linear infinite;
}

#loading-content:before {
  border: 3px solid transparent;
  border-top-color: #D4CC6A;
  border-bottom-color: #D4CC6A;
  border-radius: 50%;
  -webkit-animation: loader 3s linear infinite;
    -moz-animation: loader 2s linear infinite;
  -o-animation: loader 2s linear infinite;
  animation: loader 3s linear infinite;
}

#loading-content:after {
  border: 3px solid transparent;
  border-top-color: #84417C;
  border-bottom-color: #84417C;
  border-radius: 50%;
  -webkit-animation: loader 1.5s linear infinite;
  animation: loader 1.5s linear infinite;
    -moz-animation: loader 2s linear infinite;
  -o-animation: loader 2s linear infinite;
}

@-webkit-keyframes loaders {
  0% {
    -webkit-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@keyframes loader {
  0% {
    -webkit-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

#content-wrapper {
  color: #FFF;
  position: fixed;
  left: 0;
  top: 20px;
  width: 100%;
  height: 100%;
}

#header
{
  width: 800px;
  margin: 0 auto;
  text-align: center;
  height: 100px;
  background-color: #666;
}

#content
{
  width: 800px;
  height: 1000px;
  margin: 0 auto;
  text-align: center;
  background-color: #888;
}

 @media (max-width: 900px){
  .col-5.title {
        width: 100.666667%;
    }
    .col-3.c-name {
        width: 100%;
    }
    
    .tag-data {
        margin-left: 50px;
    }
}
    </style>
@endpush

@section('content')
  @canany('view_company_dashboard','view_candidate_dashboard','view_jobs_gigs_dashboard')
    <div class="row ">
        <div class="col">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card date-card1 pull-right">
                        <div class="card-body date-body">
                            <div id="reportrange" style="background: #fff;cursor: pointer; padding: 5px 10px;width: 100%">
                                <img class="indicator-icon" src="{{ asset('assets/images/others/calendar.png') }}">&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                    @can('view_company_dashboard')
                      <li class="nav-item">
                        <a class="nav-link active" id="companies-line-tab" data-bs-toggle="tab" href="#companies" role="tab" aria-controls="companies" aria-selected="true">Companies</a>

                      </li>
                    @endcan
                    @can('view_candidate_dashboard')
                      <li class="nav-item">
                        <a class="nav-link" id="candidates-line-tab" data-bs-toggle="tab" href="#candidates" role="tab" aria-controls="candidates" aria-selected="false">Candidates</a>

                      </li>

                    @endcan
                     
                       @can('view_jobs_gigs_dashboard')
                      <li class="nav-item">
                        <a class="nav-link" id="jobs-and-gigs-line-tab" data-bs-toggle="tab" href="#jobs-and-gigs" role="tab" aria-controls="jobs-and-gigs" aria-selected="false">Jobs & Gigs</a>
                      </li>
                      @endcan
                                    
                    </ul>
                    <!-- Code for Companies data............... -->
                    @can('view_company_dashboard')
                    <div class="tab-content mt-3" id="lineTabContent">
                        <div class="tab-pane fade show active companies loading" id="companies" role="tabpanel" aria-labelledby="companies-line-tab"> 
                            
                            
                        </div>
                    @endcan
                    <!--  end Code for Companies data............... -->
                    <!--  Code for cadidates data............... -->
                     @can('view_candidate_dashboard')
                    <div class="tab-pane fade candidates" id="candidates" role="tabpanel" aria-labelledby="candidates-line-tab">
                        <div class="text-center" >
                          <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                          </div>
                        </div>
                    </div>
                    @endcan
                    <!--  end Code for cadidates data............... -->
                    <!--  Code for  TT Admin data............... -->
                    <div class="tab-pane fade" id="tt-admin" role="tabpanel" aria-labelledby="tt-admin-line-tab">
                      TT Admin
                    </div>
                    <!--  end Code for TT Admin data............... -->

                    <!--  Code for  TT Admin data............... -->
                   <!--   <div class="tab-pane fade" id="evaluators" role="tabpanel" aria-labelledby="evaluators-line-tab">
                      Evaluators
                    </div> -->
                    <!--  end Code for TT Admin data............... -->
                    <!--  Code for  jobs-and-gigs data............... -->
                    @can('view_jobs_gigs_dashboard')
                    <div class="tab-pane fade jobs-and-gigs" id="jobs-and-gigs" role="tabpanel" aria-labelledby="jobs-and-gigs-line-tab">
                        <div class="text-center" >
                          <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                          </div>
                        </div>
                    </div>
                    @endcan

                </div>
               
            </div>
          
        </div>
        <!--col-->
    </div>
  @endcanany
    <!--row-->

<script type="text/javascript">
    

    var current_tab = 'companies';

    $(function() {

        var start = moment("{{$data['start_date']}}");
        var end = moment("{{$data['end_date']}}");
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            showWeekNumbers: false,
            ranges: {
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
               'This Year': [moment().startOf('year'), moment().endOf('year')],
               'Last Year': [moment().subtract(1,'years').startOf('year'), moment().subtract(1,'years').endOf('year')],
            }
        }, function(start, end, label) {
            let start_date = start.format('Y/M/D');
            let end_date = end.format('Y/M/D');
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            if (current_tab == 'companies') {
                getCompaniesData(start_date,end_date);
            }else if(current_tab == 'candidates') {
                getCandidatesData(start_date,end_date);
            }else if(current_tab == 'jobsGigs'){

                getJobsAndGigsData(start_date,end_date);
            }

        });

        cb(start, end);

    });

    $(document).ready(function() {

        getCompaniesData("{{$data['start_date']}}","{{$data['end_date']}}");

        $('#candidates-line-tab').on('click',function(){
            current_tab = 'candidates';
            
            getCandidatesData("{{$data['start_date']}}","{{$data['end_date']}}")
             
        })

        $('#jobs-and-gigs-line-tab').on('click',function(){
            current_tab = 'jobsGigs';
            getJobsAndGigsData("{{$data['start_date']}}","{{$data['end_date']}}");
        });

    })

    function getCompaniesData(start_date,end_date) {
        $('body').find('.spinner-border').show();
        var start = start_date;
        var end = end_date;
        var params = "?start="+start+ "&end="+end;
        var url = "{{ route('admin.dashboard.companies') }}"+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.spinner-border').hide();
                if (data) {
                    $('body').find('.companies').html(data);
                }
            },
        });
    }

    function getCandidatesData(start_date,end_date) {
        $('body').find('.spinner-border').show();
        var start = start_date;
        var end = end_date;
        var params = "&start="+start+ "&end="+end;
        var url = "{{ route('admin.candicates-data',['type'=>'candicates']) }}"+params;
        $.ajax({
            type: "post",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.spinner-border').hide();
                if (data) {
                    $('body').find('.spinner-border').removeClass('loading');
                    $('body').find('.candidates').html(data);
                }
            },
        });
    }


    function getJobsAndGigsData(start_date,end_date) {
        $('body').find('.spinner-border').show();
        var start = start_date;
        var end = end_date;
        var params = "?start="+start+ "&end="+end;
        var url = "{{ route('admin.dashboard.jobsGigs') }}"+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.visually-hidden').hide();
                if (data) {
                    $('body').find('.spinner-border').removeClass('loading');
                    $('body').find('.jobs-and-gigs').html(data);
                }
            },
        });
    }

</script>
    

@endsection  
