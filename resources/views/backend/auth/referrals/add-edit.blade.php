@extends('backend.layouts.app')

@section('title', __('labels.backend.access.referral.management') . ' | ' . __('labels.backend.access.referral.create'))

@section('content')
    @push('plugin-styles')
        <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />

    @endpush
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Create Referral Program</h6>
                    @if(isset($id))
                        <form class="forms-sample" id="referralForm" action="{{ route('admin.auth.referral.referral-update',$id) }}" method="POST" enctype="multipart/form-data">
                    @else
                        <form class="forms-sample" id="referralForm" autocomplete="off" action="{{ route('admin.auth.referrals.store') }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Referral  User Type</label>
                            <select required class="form-select" id="user_type" name="user_type">
                                <option selected disabled>Select Referral  User Type</option>
                                <option {{ isset($referral->user_type) && $referral->user_type == 'candidate' ? 'selected' : '' }} value="candidate">Candidate</option>
                                <option {{ isset($referral->user_type) && $referral->user_type == 'companies' ? 'selected' : '' }} value="companies">Companies</option>
                            </select>
                            @if ($errors->has('user_type'))
                                <div class="error text-danger">{{ $errors->first('user_type') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Targeted Audience</label>
                            <select required class="form-select" id="target_audience" name="target_audience">
                                <option selected disabled>Select Targeted Audience</option>
                                <option {{ isset($referral->target_audience) && $referral->target_audience == 'candidates' ? 'selected' : '' }} value="candidates">Candidates</option>
                                <option {{ isset($referral->target_audience) && $referral->target_audience == 'companies' ? 'selected' : '' }} value="companies">Companies</option>
                            </select>
                            @if ($errors->has('target_audience'))
                                <div class="error text-danger">{{ $errors->first('target_audience') }}</div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Program Image(Optional)</label>
                                    <input class="form-control" name="img" type="file" id="myDropify"/>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="program_name" class="form-label">Program Name</label>
                            <input type="text" class="form-control" name="program_name" id="program_name" placeholder="Program Name" value="{{ old('program_name', $referral->program_name) }}" required>
                            @if ($errors->has('program_name'))
                                <div class="error text-danger">{{ $errors->first('program_name') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="program_description" class="form-label">Program Description</label>
                            <textarea rows="5" type="text" class="form-control" id="program_description" autocomplete="off" placeholder="Program Description" name="program_description" value="{{ old('program_description', $referral->program_description) }}" required>{{ old('program_description', $referral->program_description) }}</textarea>
                            @if ($errors->has('program_description'))
                                <div class="error text-danger">{{ $errors->first('program_description') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="limit_per_user" class="form-label">Limit per user(Optional)</label>
                            <input type="number" class="form-control" name="limit_per_user" id="limit_per_user" autocomplete="off" value="{{ old('limit_per_user', $referral->limit_per_user) }}">
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="text" min="{{ isset($referral->start_date) ? date('Y-m-d', strtotime($referral->start_date)) : date('Y-m-d') }}" max="{{ isset($referral->end_date) ? date('Y-m-d', strtotime($referral->end_date)) : '' }}" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', isset($referral->start_date) ? date('d-m-Y', strtotime($referral->start_date)) : '') }}" required>
                                    @if ($errors->has('start_date'))
                                        <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="text" min="{{ isset($referral->start_date) ? date('Y-m-d', strtotime($referral->start_date)) : date('Y-m-d') }}" name="end_date" class="form-control" id="end_date" value="{{ old('end_date', isset($referral->end_date) ? date('d-m-Y', strtotime($referral->end_date)) : '') }}" required>
                                    @if ($errors->has('end_date'))
                                        <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <h6>Reward Details</h6>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="TT Cash">TT Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="text" name="amount" class="form-control" value="{{ old('amount', $referral->amount) }}" required>
                                    @if ($errors->has('amount'))
                                        <div class="error text-danger">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">No.of successful referrals</label>
                                    <input type="text" name="eligiblity_number" class="form-control" value="{{ old('eligiblity_number', $referral->eligiblity_number) }}" required>
                                    @if ($errors->has('eligiblity_number'))
                                        <div class="error text-danger">{{ $errors->first('eligiblity_number') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <button class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $("#start_date").change(function(){
            let start_date = $("#start_date").val();
            document.getElementById("end_date").setAttribute("min", start_date);
        });

        $("#end_date").change(function(){
            let end_date = $("#end_date").val();
            document.getElementById("start_date").setAttribute("max", end_date);
        });

        $(document).ready(function() {
            $("#referralForm").validate({
                // rules: {
                //     program_name: "required",
                // }
            });
        });

        $(function() {
            $( "#start_date" ).datepicker({
                dateFormat: "dd-mm-yy",
                minDate: new Date("{{ isset($referral->start_date) ? date('Y-m-d', strtotime($referral->start_date)) : date('Y-m-d') }}"),
                maxDate : new Date("{{ isset($referral->end_date) ? date('Y-m-d', strtotime($referral->end_date)) : '' }}"),
                
                onSelect: function( selectedDate ) {
                    if(this.id == 'start_date'){
                      var dateMin = $('#start_date').datepicker("getDate");
                      $('#end_date').datepicker("option","minDate",dateMin);
                    }else{
                      var dateMin = $('#end_date').datepicker("getDate");
                      $('#start_date').datepicker("option","maxDate",dateMin);
                    }
                }
            });
            $( "#end_date" ).datepicker({
                dateFormat: "dd-mm-yy",
                minDate: new Date("{{ isset($referral->start_date) ? date('Y-m-d', strtotime($referral->start_date)) : date('Y-m-d') }}"),
                
                onSelect: function( selectedDate ) {
                    if(this.id == 'start_date'){
                      var dateMin = $('#start_date').datepicker("getDate");
                      $('#end_date').datepicker("option","minDate",dateMin);
                    }else{
                      var dateMin = $('#end_date').datepicker("getDate");
                      $('#start_date').datepicker("option","maxDate",dateMin);
                    }
                }
            });
        }); 

    </script>
@endsection
