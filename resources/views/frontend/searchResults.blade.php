@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | Find Jobs')

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
    .fancy{
        font-family: 'Pacifico', cursive;
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
    .titleCase{
        text-transform: capitalize;
    }
</style>
@section('content')
    @include('frontend.includes.search')

    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 align-self-center">
            {{-- @php dd($results); @endphp --}}
            @if(count($results))
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job</th>
                        <th style="width:40%;">Skill set</th>
                        <th>Location</th>
                        @if($logged_in_user && $logged_in_user->isCandidate())
                            <th>Company</th>
                        @endif
                        <th>Job-Type</th>
                        @if($logged_in_user && $logged_in_user->isCandidate())
                            <th>Posted Date</th>
                        @endif
                        <th>View Details</th>
                        @if($logged_in_user && $logged_in_user->isCandidate())
                            <th>Your WP match to the Job</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    {{-- {{dd($results)}} --}}
                        @foreach($results as $job)
                        <tr>
                            <td>{{ $job->uid }}</td>
                            <td class="titleCase">{{$job->title}}</td>
                            <td class="titleCase">{{ strlen($job->skills) > 200 ? substr($job->skills, 0, 200).'...' : $job->skills}}</td>
                            <td class="titleCase">{{$job->location}}</td>
                            
                            @if($logged_in_user && $logged_in_user->isCandidate())
                            <td>{{$job->companyDetails->name}}</td>
                            @endif
                            
                            <td>{{$job->type}}</td>

                            @if($logged_in_user && $logged_in_user->isCandidate())
                            <td>{{$job->updated_at}}</td>
                            @endif

                            <td>
                                @if(auth()->guest())
                                    <a href="{{route('frontend.auth.login')}}" target='_blank'>Login to view details</a>
                                @else
                                <a href="{{route('frontend.user.viewJobDetails', ['id' => $job->id])}}" target='_blank'><i class="fas fa-eye"></i> View job details</a>
                                @endif
                            </td>

                            @if($logged_in_user && $logged_in_user->isCandidate())
                            @php $rating = rand(20, 100);@endphp
                            <td><div class="progress-bar" role="progressbar" style="width: {{ $rating }}%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">{{ $rating }}%</div></td>
                            @endif
                        </tr>
                        @endforeach
                    {{-- @else
                        <tr>
                            @if($logged_in_user && $logged_in_user->isCandidate())
                            <td colspan="8">No result found, please try changing your search parameters!!</td>
                            @else
                            <td colspan="5">No result found, please try changing your search parameters!!</td>
                            @endif
                        </tr>
                    @endif --}}
                </tbody>
            </table>
            {{ $results instanceof \Illuminate\Pagination\LengthAwarePaginator ? $results->links() : '' }}<br/>
            @else
                <h1 class="text-center align-center" style="margin-top: 30vh;"><i class="fas fa-info-circle"></i> No results found. Please modify your search criteria and try again.</h1>
            @endif
        </div>
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
            @if(array_key_exists('updated_at', $params))
                $('input[name="updated_at"]').flatpickr({
                    defaultDate: '{{$params["updated_at"]}}',
                    maxDate: 'today'
                });
            @else
                $('input[name="updated_at"]').flatpickr();
            @endif            
             @if(array_key_exists('type', $params))
                $('select[name=type]').val('{{$params["type"]}}');
             @endif

             @if(array_key_exists('duration', $params))
                $('select[name=duration]').val('{{$params["duration"]}}');
             @endif

             @if(array_key_exists('work_authorization', $params))
                $('select[name=work_authorization]').val('{{$params["work_authorization"]}}');
             @endif

             @if(array_key_exists('joining', $params))
                $('select[name=joining]').val('{{$params["joining"]}}');
             @endif

             @if(array_key_exists('domain', $params))
                $('select[name=domain]').val('{{$params["domain"]}}');
             @endif
        });
    </script>
@endpush
