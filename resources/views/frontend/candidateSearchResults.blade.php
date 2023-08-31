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
    @include('frontend.includes.searchCandidates')

    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 align-self-center overflow-auto">
            {{-- @php dd($results); @endphp --}}
            @if(count($results))
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Candidate ID</th>
                        <th>Name</th>
                        <th>Skill set</th>
                        <th>Experience</th>
                        <th>Work Authorization</th>
                        <th>Last Updated</th>
                        {{-- @if($logged_in_user && ($logged_in_user->isCompanyAdmin() || $logged_in_user->isCompanyUser)) --}}
                        <th>View WP</th>
                        {{-- @endif --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- {{dd($results)}} --}}
                        @foreach($results as $candidate)
                        <tr>
                            <td>{{ $candidate->user->uid }}</td>
                            <td class="titleCase">{{$candidate->user->full_name}}</td>
                            <td class="titleCase">{{implode(', ', $candidate->skillList)}}</td>
                            <td class="titleCase">{{$candidate->experience == 0 ? 'NA' : (($candidate->experience > 0 && $candidate->experience/12 > 0) ? (int) ($candidate->experience/12) . ' Y' : '') }} {{ ($candidate->experience > 0 && $candidate->experience%12) > 0 ? (int) ($candidate->experience%12). ' M' : '' }}</td>
                            <td class="titleCase">{{$candidate->work_authorization}}</td>
                            <td class="titleCase">{{$candidate->lastUpdated}}</td>                            
                            <td>
                                @if(auth()->guest())
                                    <a href="{{route('frontend.auth.login')}}" target='_blank'>Login to view</a>
                                @else
                                <a href="{{route('frontend.user.getWorkProfile', ['user_id' => $candidate->user->id])}}" target='_blank'><i class="fas fa-eye"></i> View WP</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
            @else
                <h1 class="text-center align-center" style="margin-top: 30vh;"><i class="fas fa-info-circle"></i> No results found. Please modify your search criteria and try again.</h1>
            @endif
        </div>
    </div>
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
            @if(array_key_exists('work_profiles_work_authorization', $params))
                $('select#work_authorization').val($.parseJSON('{!! json_encode($params["work_profiles_work_authorization"])!!}')).trigger('change');
            @endif
        });
    </script>
@endpush
