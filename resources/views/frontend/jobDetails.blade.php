@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | Job Details')

@push('after-styles')
    <style type="text/css">
        .list-group-item{
            padding: 0.4rem;
            border: none;
        }
        
        .viewActive{
            color: #007bff;
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
        .form-inline label{
            justify-content: flex-start;
        }
        h5{
            text-transform: capitalize;
            font-weight: bold;
        }
        .custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before{
            background-color: #007bff;
        }
        .custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label{
            color: #212539;
        }
        label{
            font-weight: bold; 
        }
        .socialLogo{
            font-size: 1.5rem;
        }
    </style>
@endpush

@section('content')
    @php $company = $job->companyDetails; @endphp
    <div class="row p-3 mb-3 mt-n4" style="background-color:#4388ee5c!important;">
        
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <h2>Details for - <span class="font-weight-bold">{{$job->title}}</span>
                <span class="text-danger flagJob" title='Report/Flag this Posting' style="cursor: pointer;" data-toggle="modal" data-target="#flagJob"><i class="fas fa-exclamation-triangle"></i></span></h2>
            <h6>Job ID: {{ $job->uid }} | Posted on: {{$job->updated_at}}</h6>
        </div>
        {{-- <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"> --}}
            {{-- <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}"><i class="fas fa-chevron-left"></i> Back to Job list</a> --}}
        {{-- </div> --}}
    </div>
    <div class="container-fluid border-left" style="padding-left: 3vw;">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="top: -7vh;">
                {{-- <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 position-absolute text-center"> --}}
                    @if($company->logo != null)
                    <div class="row">
                        <img src="{{'/getImage/' . $company->logo . '/logos'}}" class="rounded mx-auto d-block img-thumbnail float-right" style="right: 1.5rem;" width="200" alt="{{$company->name}} Logo">
                    </div>
                    @endif
                    <div class="row mt-2 p-2 animated fadeInUp text-center">
                        <h4 class="w-100">About the Company</h5>
                        <h3 class="w-100"><a class="font-weight-bold" style="cursor: help" title="Visit Company's Website" target="_blank" href="{{$company->website}}">{{$company->name}}</a></h5>
                    </div>
                    <div class="row my-1 animated fadeInUp">
                        <div class="col">
                            <hr/>
                            <h6 class="text-justify" style="font-size: 1rem;">{{$company->description}}</h6>
                        </div>
                    </div>
                    @if($company->facebook != null || $company->twitter != null || $company->linkedin != null)
                    <hr/><div class="ml-1 mt-2 animated fadeInUp text-center">
                        <span class="font-weight-bold">Social: </span>
                        @if($company->facebook != null)
                            <a class="mx-2 socialLogo" href="{{$company->facebook}}" target="_blank"><i class="fab fa-facebook-square"></i></a>
                        @endif
                        @if($company->twitter != null)
                            <a class="mr-2 socialLogo" href="{{$company->twitter}}" target="_blank"><i class="fab fa-twitter-square"></i></a>
                        @endif
                        @if($company->linkedin != null)
                            <a class="mr-2 socialLogo" href="{{$company->linkedin}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                        @endif
                    </div>
                    @endif
                    <hr/>
                    <div class="mt-2 animated fadeInUp text-center">
                        <span class="font-weight-bold mr-2">Website: </span>
                        <a href="{{$company->website}}" target="_blank">{{$company->website}}</a>
                    </div>
                {{-- </div> --}}
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9 border-left pl-5 ml-5">
                <div class="row p-2">
                    <div class="col form-group mt-3">
                        <h5>Job Description</h5>
                        <h5 class="font-weight-normal">{{$job->description}}</h5>
                    </div>
                </div>
                <div class="row p-2">
                    <div class="col animated fadeInLeft">
                        <div class="form-group">
                            <h5>Required Skills-set</h5>
                            <h5 class="font-weight-normal">{{$job->skills}}</h5>
                        </div>
                        <div class="form-group">
                            <h5>Additional Skills-set</h5>
                            <h5 class="font-weight-normal">{{$job->askills}}</h5>
                        </div>
                        <div class="form-group">
                            <h5>Job Type</h5>
                            <h5 class="font-weight-normal">{{$job->type}}</h5>
                        </div>
                        {{-- <div class="form-group">
                            <label for='salary'>Salary</label>
                            <h5 class="font-weight-normal">{{$job->salary}}</h5>
                        </div> --}}
                        <h5>Benefits</h5>
                        {{-- <div class="form-inline"> --}}
                            <div class="ro">
                                @if($job->f401K)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="401K" {{$job->f401K ? 'checked' : ''}}
                                        disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="401K">401K</h5>
                                </div>
                                @endif
                                @if($job->medical)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="medical_insurance"
                                        {{$job->medical ? 'checked' : ''}} disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="medical_insurance">Medical Insurance</h5>
                                </div>
                                @endif
                                @if($job->dental)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="dental_insurance" {{$job->dental ? 'checked' : ''}}
                                        disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="dental_insurance">Dental Insurance</h5>
                                </div>
                                @endif
                            {{-- </div> --}}
                            {{-- <label for='benefits' class="col-3"></label> --}}
                            {{-- <div class="col-9 row"> --}}
                                @if($job->life_coverage)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="life_coverage"
                                        {{$job->life_coverage ? 'checked' : ''}} disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="life_coverage">Life Coverage</h5>
                                </div>
                                @endif
                                @if($job->maternity_leave)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="maternity_leave"
                                        {{$job->maternity_leave ? 'checked' : ''}} disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="maternity_leave">Maternity Leave</h5>
                                </div>
                                @endif
                                @if($job->paternity_leave)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="paternity_leave"
                                        {{$job->paternity_leave ? 'checked' : ''}} disabled="disabled">
                                    <h5 class="custom-control-label font-weight-normal" for="paternity_leave">Paternity Leave</h5>
                                </div>
                                @endif
                            </div>
                        {{-- </div> --}}
                    </div>
                    <div class="col animated fadeInRight">
                        <div class="form-group">
                            <h5>Work-Authorization</h5>
                            <h5 class="font-weight-normal">{{$job->work_authorization}}</h5>
                        </div>
                        <div class="form-group">
                            <h5>Work Location</h5>
                            <h5 class="font-weight-normal">{{$job->location}}</h5>
                        </div>
                        <div class="form-group">
                            <h5 for="telecommute">Telecommute</h5>
                            <h5 class="font-weight-normal">{{$job->telecommute ? 'Yes' : 'No'}}</h5>
                            {{-- <input type="checkbox" id="toggle" name="telecommute" class="checkbox" disabled="disabled" {{$job->telecommute ? 'checked' : ''}} /> --}}
                            {{-- <label for="toggle" class="switch"></label> --}}
                        </div>
                        <div class="form-group">
                            <h5>Min. Experience Required</h5>
                            <h5 class="font-weight-normal">{{$job->experience_req == 0 ? 'Fresher' : $job->experience_req . ' Years'}}</h5>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-5 animated fadeInUp">
                    <div class="col text-center">
                        @php $applied = False; $saved = False; @endphp
                        {{-- {{dd($candidateJob)}} --}}
                        @if($candidateJob != null)
                            @php
                                if($candidateJob->applied){$applied = True;}
                                if($candidateJob->saved){$saved = True;}
                            @endphp
                        @endif
                        @if($applied)
                            <button type="button" class="btn btn-outline-success mr-3" disabled="disabled"><i class="fas fa-check"></i> Applied</button>
                        @else
                            <button type="button" class="btn btn-success mr-3" id='applyJobBtn' data-target='#applyModal' data-toggle='modal'><i class="fas fa-reply"></i> Apply</button>
                        @endif

                        <button type="button" class="btn btn-warning ladda-button" id='saveJob' data-style='zoom-in' title="{{ $saved ? 'Click to un-save' : 'Click to Save' }}">
                            @if($saved)
                                <i class="fas fa-check"></i> Saved <i class="fas fa-info-circle"></i>
                            @else
                                <i class="fas fa-save"></i> Save for later
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flag Job --}}
    <div class="modal fade" id="flagJob" tabindex="-1" role="dialog" aria-labelledby="flagJobModalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Flag - {{$job->title}} ({{$company->name}})</h4>
                    <div class="row">
                        <div class="col p-3">
                            <div class="form-group row">
                                <label class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center" for="flag_type">Issue</label>
                                <select class="form-control col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" id="flag_type">
                                    <option value="Incorrect details">Incorrect Details</option>
                                    <option value="Post Expired">Post Expired</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col p-3">
                            <div class="form-group">
                                <label for="flag_msg">Additional Information<i>(Optional)</i></label>
                                <textarea class="form-control" rows="5" id='flag_msg'
                                    placeholder="Please include your issue in more detail, if possible."></textarea>
                            </div>
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-success ladda-button reportJob" data-style='zoom-in'><i
                                class="fas fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>
                            Cancel</button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    {{-- Apply Job --}}
    <div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Apply for {{$job->title}} ({{$company->name}})</h4>
                    <div class="row">
                        <div class="col p-3">
                            <div class="form-group">
                                <label for="recruiter_msg">Message to Recruiter</label>
                                <textarea class="form-control" rows="5" id='recruiter_msg' placeholder="Your Custom Message for the recruiter goes here! (optional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-success ladda-button applyJob" data-style='zoom-in'><i class="fas fa-reply"></i> Apply</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-scripts')
    <script type="text/javascript">
        $(function(){
            $('button.applyJob').on('click', function(){
                let loader = Ladda.create(this);
                loader.start();
                $.post('{{route("frontend.candidate.applyJob")}}', {
                    '_token': '{{csrf_token()}}',
                    jid: '{{$job->id}}',
                    recruiter_msg: $('#recruiter_msg').val()
                }).done(function(e){
                    if(e == 'success'){
                        toastr.success('Application for this job has been submitted!!', 'Success!');
                        $('#applyJobBtn').html('<i class="fas fa-check"></i> Applied').removeAttr('data-target').removeAttr('data-toggle');
                        $('#applyModal').modal('hide');
                    }else{
                        toastr.error('Your application for this job cannot be submitted at the moment!! Please try-again later or contact support!!', 'Error!');
                    }
                    loader.stop();
                });
            });

            $('button.reportJob').on('click', function(){
                let loader = Ladda.create(this);
                loader.start();
                $.post('{{route("frontend.user.reportJob")}}', {
                    '_token': '{{csrf_token()}}',
                    jid: '{{$job->id}}',
                    flag_type: $('#flag_type').val(),
                    flag_msg: $('#flag_msg').val()
                }).done(function(e){
                    if(e == 'success'){
                        toastr.success('Job Reported!!', 'Success!');
                        $('#flagJob').modal('hide');
                        // $('.flagJob').remove();
                        // $('#applyJobBtn').html('<i class="fas fa-check"></i> Applied').removeAttr('data-target').removeAttr('data-toggle');
                    }else{
                        toastr.error('Job cannot be reported at the moment!! Please try-again later or contact support!!', 'Error!');
                    }
                    loader.stop();
                });
            });

            $('button#saveJob').on('click', function(){
                let content = $(this).text();
                if(content.indexOf('Saved') != -1){
                    content = 0;
                }else{
                    content = 1;
                }
                let loader = Ladda.create(this);
                loader.start();
                $.post('{{route("frontend.candidate.saveJob")}}', {'_token': '{{csrf_token()}}', jid: '{{$job->id}}'}).done(function(e){
                    if(e == 'success'){
                        if(content == 0){
                            toastr.info('Job removed from saved list!!', 'Success!');
                            $('#saveJob').html('<i class="fas fa-save"></i> Save for later').attr('title', 'Click to Save');
                        }else{
                            toastr.success('Job saved for later!!', 'Success!');
                            $('#saveJob').html('<i class="fas fa-check"></i> Saved <i class="fas fa-info-circle"></i>').attr('title', 'Click to un-save');
                        }
                    }else{
                        toastr.error('Job cannot be saved at the moment, please try-again later or contact support!!', 'Error!');
                    }
                    loader.stop();
                });
            });
        });
    </script>
@endpush