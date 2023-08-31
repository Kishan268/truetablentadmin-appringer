@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | Work Profile')

<style type="text/css">
    .fancy{
        font-family: 'Pacifico', cursive;
    }
    .titleCase{
        text-transform: capitalize;
    }
    label{
        font-weight: bold;
    }
    .container-fluid{
        padding-top: 1rem;
        background-color: #f0f0f0;
    }
    .container{
        border: 1px solid #eeeeee;
        background-color: #fff;
        box-shadow: 0 0 3px 1px #B6B6B6;
    }
    hr{
        border-top: 3px solid rgb(0,0,0,74%);
    }
    @media print{
        .container{
            padding: 1rem !important;
        }
        .print, .fullPrint {
            max-width: 100% !important;
            width: 100% !important;
            display: block !important;
            border: none!important;
        }
        .app-footer, div.btns, div.noPrint{
            display: none;
        }
        a:not(.btn){
            text-decoration: none!important;
        }
        div.personalDetails{
            padding: 0.8rem !important;
        }
    }
    @media only screen and (max-width: 600px) {
        .printViewBtns{
            margin-top: 1rem;
        }
    }
    .unlockEvaluation{
        z-index: 1;
        margin-top: 10%;
        font-size: 2rem;
        position: absolute;
        color: #6c757d!important;
    }
</style>

@isset($no_view)
<style type="text/css">
    .container-fluid{
        height: 80vh!important;
    }
</style>
@endisset

@section('content')
    @isset($no_view)
        <h1 class="text-center" style="margin-top: 20vh;"><i class="fas fa-exclamation-triangle"></i> You don't have enough TT-Cash for viewing work profiles.</h1>
        <h2 class="text-center" style="margin-top: 10vh;"><i class="fas fa-info-circle"></i> Try refreshing this page after purchasing TT-Cash.</h2>
    @else
    <div class="container p-4 print">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                <div class="row onlyMobile">
                    <div class="col text-center">
                        <img width="170"
                            src="{{ $user->avatar_location != null ? url('storage/'.$user->avatar_location) : 'https://www.gravatar.com/avatar/fa645c52bda684621480070cf6ba54dd.jpg?s=80&d=mm&r=g' }}"
                            class="img-circle width-100" alt="" />
                    </div>
                </div>
                
                {{-- Personal Info --}}
                <div class="row onlyMobile">
                    <div class="col p-4 personalDetails">
                        <h5 class="mt-2" style="text-transform: lowercase;" title="{{$user->email}}"><i class="fas fa-at"></i> {{strlen($user->email) > 15 ? substr($user->email, 0, 15).'...' : $user->email}}</h5>
                        <h5 class="mt-3"><i class="fas fa-phone"></i> {{$user->contact}}</h5>
                        <h5 class="mt-3"><i class="fas fa-map-marker-alt"></i> {{$user->location}}</h5>
                    </div>
                </div>
                {{-- Name, Designation --}}
                <div class="row">
                    <div class="col">
                        <h3 class="font-weight-bold">{{$user->full_name}} <small style="font-size: 60%;"> {{ env('APP_NAME') }} ID: {{ $user->uid }}</small>
                            <div class="btn-group ml-5 btns printViewBtns" role="group" aria-label="View / Download">
                                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i> Print</button>
                                <a target="_blank" href="/getCandidateResume/{{$wp['cvLink']}}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i> Résumé</a>
                            </div>
                        </h3>
                        @if($user->designation != null) <h5 class="text-muted">{{ $user->designation }}</h5> @endif
                    </div>
                </div>

                {{-- Summary --}}
                <div class="row">
                    <div class="col my-2">
                        <p>{{ $wp->summary }}</p>
                    </div>
                </div>

                {{-- Work Experience --}}
                @if(count($wp->experiences) > 0)
                <div class="row fullPrint">
                    <div class="col">
                        <h3>Work Experience</h3><hr/>
                            {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action"> --}}
                                {{-- <p class="mb-1 text-center">No experience information has been provided this candidate!</p> --}}
                            {{-- </a> --}}
                        @foreach($wp->experiences as $experience)
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action p-1 border-0">
                            <div class="d-flex w-100">
                                <span class="h5 mb-1">{{$experience['title']}}</span>&nbsp;at&nbsp;<span class="h5 font-weight-bold">{{$experience['shortDesc']}}</span>
                                <span class="position-absolute noMobile" style="right: 1px;">{{$experience['remarks']}}</span>
                            </div>
                            <div class="onlyMobile float-right">
                                <span class="position-block onlyMobile" style="right: 1px;">{{$experience['remarks']}}</span>
                            </div>
                            @php $responsibilities = explode(PHP_EOL, $experience['longDesc']); @endphp
                            <ul>
                                @foreach($responsibilities as $responsibility)
                                <li>{{ $responsibility }}</li>
                                @endforeach
                            </ul>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(count($wp->educations) > 0)
                <div class="row mt-4 fullPrint">
                    <div class="col">
                        <h3>Education</h3>
                        <hr/>
                        {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action"> --}}
                        {{-- <p class="mb-1 text-center">No experience information has been provided this candidate!</p> --}}
                        {{-- </a> --}}
                        @foreach($wp->educations as $education)
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action p-1 border-0">
                            <div class="d-flex w-100">
                                <span class="h5 mb-1">{{$education['title']}}</span>&nbsp;at&nbsp;<span
                                    class="h5 font-weight-bold">{{$education['shortDesc']}}</span><span
                                    class="position-absolute noMobile" style="right: 1px;">{{$education['remarks']}}</span>
                            </div>
                            <div class="onlyMobile float-right">
                                <span class="position-block onlyMobile" style="right: 1px;">{{$experience['remarks']}}</span>
                            </div>
                            @if($education['longDesc'] != null)
                            @php $descs = explode(PHP_EOL, $education['longDesc']); @endphp
                            <ul>
                                @foreach($descs as $desc)
                                <li>{{ $desc }}</li>
                                @endforeach
                            </ul>
                            @endisset
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(count($wp->certifications) > 0)
                <div class="row mt-4 fullPrint">
                    <div class="col">
                        <h3>Certifications</h3>
                        <hr />
                        {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action"> --}}
                        {{-- <p class="mb-1 text-center">No experience information has been provided this candidate!</p> --}}
                        {{-- </a> --}}
                        @foreach($wp->certifications as $certification)
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action p-1 border-0">
                            <div class="d-flex w-100">
                                <span class="h5 mb-1 font-weight-bold">{{$certification['title']}}</span>
                                <span class="mx-2">from</span>
                                <span class="h5">{{$certification['shortDesc']}}</span>
                                <span class="text-muted ml-2 noMobile">({{$certification['remarks']}})</span>
                                <span class="position-absolute text-success" title="Certification verified by {{ env('APP_NAME') }}" style="right: 1px; font-size:2rem; cursor:help;"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <div class="onlyMobile float-right mt-2">
                                <span class="position-block onlyMobile" style="right: 1px;">{{$certification['remarks']}}</span>
                            </div>
                            @php $descs = explode(PHP_EOL, $certification['longDesc']); @endphp
                            <ul>
                                @foreach($descs as $desc)
                                <li>{{ $desc }}</li>
                                @endforeach
                            </ul>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(count($wp->awards) > 0)
                <div class="row mt-4 fullPrint">
                    <div class="col">
                        <h3>Awards & Accompolishments</h3>
                        <hr />
                        {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action"> --}}
                        {{-- <p class="mb-1 text-center">No experience information has been provided this candidate!</p> --}}
                        {{-- </a> --}}
                        @foreach($wp->awards as $award)
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action p-1 border-0">
                            <div class="d-flex w-100">
                                <span class="h5 mb-1">{{$award['title']}}</span>&nbsp;from&nbsp;<span
                                    class="h5 font-weight-bold">{{$award['shortDesc']}}</span><span class="position-absolute noMobile"
                                    style="right: 1px;">{{$award['remarks']}}</span>
                            </div>
                            <div class="onlyMobile float-right mt-2">
                                <span class="position-block onlyMobile" style="right: 1px;">{{$award['remarks']}}</span>
                            </div>
                            @php $descs = explode(PHP_EOL, $award['longDesc']); @endphp
                            <ul>
                                @foreach($descs as $desc)
                                <li>{{ $desc }}</li>
                                @endforeach
                            </ul>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                {{-- Photo --}}
                <div class="row noMobile">
                    <div class="col text-center">
                        <img width="170" src="{{ $user->avatar_location != null ? url('storage/'.$user->avatar_location) : 'https://www.gravatar.com/avatar/fa645c52bda684621480070cf6ba54dd.jpg?s=80&d=mm&r=g' }}"
                            class="img-circle width-100" alt="" />
                    </div>
                </div>

                {{-- Personal Info --}}
                <div class="row noMobile">
                    <div class="col p-4 personalDetails">
                        <h5 class="mt-2" style="text-transform: lowercase;cursor:help;" title='{{ $user->email }}'><i class="fas fa-at"></i> {{strlen($user->email) > 20 ? substr($user->email, 0, 20).'...' : $user->email}}</h5>
                        <h5 class="mt-3"><i class="fas fa-phone"></i> {{$user->contact}}</h5>
                        <h5 class="mt-3"><i class="fas fa-map-marker-alt"></i> {{$user->location}}</h5>
                    </div>
                </div>

                {{-- AI Ratings --}}
                @if(false)
                <div class="row mt-2 noPrint">
                    <div class="col">
                        <h3>AI Ratings</h3>
                        <hr />
                        @if(count($wp->skills) == 0)
                            <tr>
                                <td class="text-center" colspan="3">Not Available!</td>
                            </tr>
                        @else
                            @foreach($wp->skills as $skill)
                                <div class="row">
                                    <div class="col-4" style="text-transform:capitalize;">{{$skill['title']}}</div>
                                    <div class="col-8">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $skill['remarks'] != null ? $skill['remarks'] : 0 }}%;" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100">{{ $skill['remarks'] != null ? $skill['remarks'] : 0 }}%</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                {{-- Evaluator Ratings --}}
                <div class="row mt-5 noPrint">
                    <div class="col">
                        <h3>Evaluator Ratings</h3>
                        <hr />
                        @if(count($wp->skills) == 0)
                            <tr>
                                <td class="text-center" colspan="3">Not Available!</td>
                            </tr>
                        @else
                            @if($evaluation)
                                @foreach($wp->skills as $skill)
                                    <div class="row">
                                        <div class="col-4" style="text-transform:capitalize;">{{$skill['title']}}</div>
                                        <div class="col-8">
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $skill['remarks'] != null ? $skill['remarks'] : 0 }}%;" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100">{{ $skill['remarks'] != null ? $skill['remarks'] : 0 }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            <a href="javascript:void(0);" class='unlockEvaluation text-center'>
                                <i class="fas fa-lock"></i><br/>
                                Click to Unlock
                            </a>
                            <section style="opacity:0.12;">
                                @for($i=0; $i<7; $i++)
                                <div class="row">
                                    <div class="col-4" style="text-transform:capitalize;">Skill {{$i+1}}</div>
                                    <div class="col-8">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ rand(0, 100) }}%;" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100">{{ rand(0, 100) }}%</div>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </section>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- @if(strlen($wp->evaluator_feedback) > 0) --}}
                <div class="row mt-5 noPrint">
                    <div class="col">
                        <h3>Evaluator Feedback</h3>
                        <hr />
                        @if($evaluation)
                            <p>{{ $wp->evaluation_feedback }}</p>
                        @else
                        <a href="javascript:void(0);" class='unlockEvaluation text-center' style="margin-top:25%;">
                            <i class="fas fa-lock"></i><br />
                            Click to Unlock
                        </a>
                        <section style="opacity:0.12;">
                            <p>His sample technical and sample leadership skills are matched by excellent people sample skills. He demonstrated the highest level of sample understanding of the most complex sample design and sample implementation issues at every sample stage
                            of sample development. He is also one of few sample industry experts who can explain the complex sample concepts in a very sample practical and simple way.</p>
                        </section>
                        @endif
                    </div>
                </div>
                {{-- @endif --}}
            </div>
        </div>
        {{-- <div class="row mx-auto text-center my-3">
            <div class="col-4">
                
            </div>
            <div class="col-4">
                
            </div>
            <div class="col-4">
                
            </div>
        </div> --}}
        {{-- <div class="row mt-3">
            <div class="col-4 mx-auto my-5">
                @php $work_authorization = ["citizen" => "Citizen", "GC" => "GC", "H1B" => "H1B", "h4ead" => "H4 EAD", "l2ead" => "L2 EAD", "TNVisa" => "TN Visa", "F1Opt" => "F1 Opt (STEM)"]; @endphp
                <div class="row mt-5">
                    <div class="col">
                        <div class="form-inline row">
                            <label class="col-4">Work Authorization</label>
                            <h5 class="row-8">{{$work_authorization[$wp['work_authorization']]}}</h5>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col">
                        <div class="form-inline row">
                            <label class="col-4">Resume</label>
                            <a target="_blank" href="/getCandidateResume/{{$wp['cvLink']}}" class="btn btn-info"><i class="fas fa-eye"></i> View Résumé</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    @endisset
@endsection
@push('after-scripts')
<script type="text/javascript">
    $(function(){
        $('nav.navbar-main').removeClass('mb-4');
        $('.unlockEvaluation').on('click', function(){
            Swal.fire({
                title: 'Unlock Candidate\'s Evaluation',
                html: "You'll be charged by <b>{{$chargeAmounts->evaluation_view_ttcash ?? 0}} TT-Cash</b>.<br/> This transaction will be valid until- <b>{{$evaluation_validity ?? 'NA'}}</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    axios.get('{{ route("frontend.user.buyCandidateEvaluation", ["user_id" => $wp->user_id ?? 0])}}')
                        .then(response => {
                            if(response.data == "success") window.location.reload()
                            else toastr.error('Your request cannot be processed at the moment, please try again later or contact TT support!!', 'System Error!')
                        })
                        .catch(function (error){
                            toastr.error('Your request cannot be processed at the moment, please try again later or contact TT support!!', 'System Error!');
                        });
                }
            })
        })
    });
</script>
@endpush