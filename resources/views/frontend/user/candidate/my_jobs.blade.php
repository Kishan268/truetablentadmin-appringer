@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | My Jobs')

<style type="text/css">
    .fancy{
        font-family: 'Pacifico', cursive;
    }
    .titleCase{
        text-transform: capitalize;
    }
</style>
@section('content')
    @include('frontend.includes.search')
    <div class="row justify-contents-center align-items-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 align-self-center overflow-auto">
            <h4>Applied Jobs</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job</th>
                        <th>Skill set</th>
                        <th>Location</th>
                        <th>Company</th>
                        <th>Job-Type</th>
                        <th>Posted Date</th>
                        <th>View Details</th>
                        <th>Your WP match to the Job</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{dd($results)}} --}}
                    @if(count($applied))
                        @foreach($applied as $job)
                        @php $job = $job->job; @endphp
                        <tr>
                            <td>{{ $job->uid }}</td>
                            <td class="titleCase">{{$job->title}}</td>
                            <td class="titleCase">{{$job->skills}}</td>
                            <td class="titleCase">{{$job->location}}</td>
                            <td>{{$job->companyDetails->name}}</td>
                            <td>{{$job->type}}</td>
                            <td>{{$job->updated_at}}</td>
                            <td>
                                <a href="{{route('frontend.user.viewJobDetails', ['id' => $job->id])}}" target='_blank'><i class="fas fa-eye"></i> View job details</a>
                            </td>
                            @php $rating = rand(20, 100);@endphp
                            <td><div class="progress-bar" role="progressbar" style="width: {{ $rating }}%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">{{ $rating }}%</div></td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">Jobs you'll apply to will appear here!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-contents-center align-items-center mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 align-self-center overflow-auto">
            <h4>Saved Jobs</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job</th>
                        <th>Skill set</th>
                        <th>Location</th>
                        <th>Company</th>
                        <th>Job-Type</th>
                        <th>Posted Date</th>
                        <th>View Details</th>
                        <th>Your WP match to the Job</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{dd($results)}} --}}
                    @if(count($saved))
                        @foreach($saved as $job)
                        @php $job = $job->job; @endphp
                        <tr>
                            <td>{{ $job->uid }}</td>
                            <td class="titleCase">{{$job->title}}</td>
                            <td class="titleCase">{{$job->skills}}</td>
                            <td class="titleCase">{{$job->location}}</td>
                            <td>{{$job->companyDetails->name}}</td>
                            <td>{{$job->type}}</td>
                            <td>{{$job->updated_at}}</td>
                            <td>
                                <a href="{{route('frontend.user.viewJobDetails', ['id' => $job->id])}}" target='_blank'><i class="fas fa-eye"></i> View job details</a>
                            </td>

                            @php $rating = rand(20, 100);@endphp
                            <td><div class="progress-bar" role="progressbar" style="width: {{ $rating }}%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" target='_blank'>{{ $rating }}%</div></td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">Jobs you'll apply to will appear here!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection