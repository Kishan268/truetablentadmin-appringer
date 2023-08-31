@extends('backend.layouts.app')

@section('title', __('labels.backend.access.job.management') . ' | ' . __('labels.backend.access.job.create'))

<style>
    li.select2-selection__choice {
        margin-bottom: 3px;
    }
    .fade-btn{
        opacity: 0.3;
    }
    
</style>

@section('content')
    {{-- {{ html()->form('POST', route('admin.auth.company.job.update'))->class('form-horizontal')->open() }} --}}
    <form class="form-horizontal" action="{{route('admin.auth.company.job.update')}}" method="POST" id="basic-form">
        @csrf
    <div class="card add-job">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.job.management')
                        <small class="text-muted">@lang('labels.backend.access.job.create')</small>
                    </h4>
                </div>
                <!--col-->
            </div>
            <!--row-->

            <hr>
            <input type="hidden" name="job_id" value="{{ $jobs->id}}">
            <div class="row mt-4 mb-4">
                <div class="col">

                    <div class="form-group row mt-2">
                        <label class="col-md-2 form-control-label required" for="company_id">Company</label>

                        <div class="col-md-10">
                            <select class="form-control custom-select2" name="company_id" id="company_id">
                                @if (count($companies) > 0)
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{$jobs->company_id ===  $company->id ? 'selected' :''}}>{{ $company->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('company_id'))
                                <div class="error text-danger">{{ $errors->first('company_id') }}</div>
                            @endif
                        <label id="companyid-error" style="display: none;color:red">This field is required.</label>

                        </div>
                        <!--col-->
                    </div>  
                    <div class="form-group row mt-2">
                        <label class="col-md-2 form-control-label required" for="company_id">Company User</label>

                        <div class="col-md-10 add">
                            <select id="user_id" class="form-control" name="user_id">
                                @if (count($usersWithRoles) > 0)
                                    @foreach ($usersWithRoles as $usersWithRole)
                                        <option value="{{ $usersWithRole->id }}" {{$jobs->user_id ===  $usersWithRole->id ? 'selected' :''}}>{{ @$usersWithRole->first_name .' '.@$usersWithRole->last_name ." (".@$usersWithRole->roles[0]->name .")"}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('user_id'))
                                <div class="error text-danger">{{ $errors->first('user_id') }}</div>
                            @endif
                            <label id="companyuserid-error" style="display: none;color:red">This field is required.</label>

                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 form-control-label required" for="title">Title and Description</label>

                        <div class="col-md-8">
                            <input class="form-control" type="text" name="title" id="title" placeholder="Job Title"
                                maxlength="191" autofocus="" value="{{ $jobs->title ? $jobs->title : old('title') }}">
                            @if ($errors->has('title'))
                                <div class="error text-danger">{{ $errors->first('title') }}</div>
                            @endif
                        <label id="title-error" style="display: none;color:red">This field is required.</label>

                        </div>
                        <!--col-->
                        <div class="col-md-2">
                            <button class='green-btn'>Generate JD</button>
                        </div>
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="description"></label>

                        <div class="col-md-10">
                            <textarea id="description" name="description" rows="7" class="form-control" placeholder="Description">{{ $jobs->description ? $jobs->description : old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <div class="error text-danger">{{ $errors->first('description') }}</div>
                            @endif
                            <label id="description-error" style="display: none;color:red">This field is required.</label>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label" for="reference_number">Reference Number</label>

                        <div class="col-md-10">
                            <input class="form-control" type="text" name="reference_number" id="reference_number"
                                placeholder="Reference Number" maxlength="191" autofocus=""
                                value="{{$jobs->reference_number ? $jobs->reference_number : old('reference_number') }}">
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label required" for="job_type">Job Type</label>

                        <div class="col-md-10">
                            @if (count($data['job_types']) > 0)
                                @foreach ($data['job_types'] as $job_type)
                                    <div class="form-check radio-check">
                                        <input name="job_type_id" id="job_type{{ $job_type->id }}" class="form-check-input"
                                            type="radio" value="{{ $job_type->id }}" {{$jobs->job_type_id === $job_type->id  ? 'checked' :''}}/>
                                        <label class="form-check-label" htmlFor="job_type{{ $job_type->id }}">
                                            {{ $job_type->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($errors->has('job_type_id'))
                                    <div class="error text-danger">{{ $errors->first('job_type_id') }}</div>
                                @endif
                                <label id="jobtypeid-error" style="display: none;color:red">This field is required.</label>
                            @endif
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label required" for="location">Job Location</label>

                        <div class="col-md-10">
                             <select class="form-control" multiple name="work_locations[]" id="work_locations">
                                @if(isset($locations))
                                    @foreach($locations as $work_location)
                                     <option value="{{$work_location->id}}" selected="selected">{{$work_location->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('work_locations'))
                                <div class="error text-danger">{{ $errors->first('work_locations') }}</div>
                            @endif
                            <label id="worklocations-error" style="display: none;color:red">This field is required.</label>

                        </div>
                        <!--col-->
                    </div>


                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label required" for="required_skills">Skills and Experience</label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Required Skills
                            </label>
                            <select class="form-control" multiple name="required_skills[]"  id="required_skills">
                                @if(isset($jobs->company_jod_detail))
                                    @foreach($selectedSkills as $selectedSkill)
                                     <option value="{{$selectedSkill->name}}" selected="selected">{{$selectedSkill->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            
                            @if ($errors->has('required_skills'))
                                <div class="error text-danger">{{ $errors->first('required_skills') }}</div>
                            @endif
                            <label id="requiredskills-error" style="display: none;color:red">This field is required.</label>

                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="required_skills"></label>
                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Additional Skill-Sets
                            </label>
                             <select class="form-control" multiple name="additional_skills[]"  id="additional_skills">
                                @if(isset($jobs->company_jod_detail))
                                    @foreach($additionalSkills as $additionalSkill)
                                     <option value="{{$additionalSkill->name}}" selected="selected">{{$additionalSkill->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label id="additionalskills-error" style="display: none;color:red">This field is required.</label>

                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="required_skills"></label>
                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Minimum Experience Required(in years)
                            </label>
                            <div class="input-group">
                                <input style="width: 100%" type="number" min="0"
                                    name="minimum_experience_required"
                                    value="{{ $jobs->minimum_experience_required ? round($jobs->minimum_experience_required / 12,1) : old('minimum_experience_required') }}" id="minimum_experience_required" class="form-control" />
                                @if ($errors->has('minimum_experience_required'))
                                    <div class="error text-danger">{{ $errors->first('minimum_experience_required') }}</div>
                                @endif
                                 <label id="minimumexperiencerequired-error" style="display: none;color:red">This field is required.</label>
                            </div>
                        </div>
                        <!--col-->
                    </div>


                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="required_skills"></label>
                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Maximum Experience Required(in years)
                            </label>
                            <div class="input-group">
                                <input style="width: 100%" type="number" min="0"
                                    name="maximum_experience_required"
                                    value="{{ $jobs->maximum_experience_required ? round($jobs->maximum_experience_required / 12,1) : old('maximum_experience_required') }}" id="maximum_experience_required" class="form-control" />
                                @if ($errors->has('maximum_experience_required'))
                                    <div class="error text-danger">{{ $errors->first('maximum_experience_required') }}</div>
                                @endif
                                 <label id="maximumexperiencerequired-error" style="display: none;color:red">This field is required.</label>
                            </div>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label" for="required_skills">Salary & Benefits</label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Salary Type
                            </label><br />
                            <span>For Full Time Job choose Annual and for other options choose Monthly</span>
                            <div class="row  mt-2 checkform mt-3" style="padding-left: 10px;">
                                @if (count($data['salary_types']) > 0)
                                    @foreach ($data['salary_types'] as $salary_type)
                                        <div class="col-3 form-check radio-check">
                                            <input name="salary_type_id" id="salary_type{{ $salary_type->id }}"
                                                class="form-check-input salary_type_id salary_type" type="radio"
                                                value="{{ $salary_type->id }}" {{$jobs->salary_type_id ===$salary_type->id ? 'checked':'' }}/>
                                            <label class="form-check-label" htmlFor="salary_type{{ $salary_type->id }}">
                                                {{ $salary_type->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                                @if ($errors->has('salary_type_id'))
                                    <div class="error text-danger">{{ $errors->first('salary_type_id') }}</div>
                                @endif
                                <label id="salarytypeid-error" style="display: none;color:red">This field is required.</label>
                            </div>
                        </div>
                        <!--col-->
                    </div>
                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="required_skills"></label>
                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Salary Range
                            </label>
                            <div class="row  mt-2">
                                <div class="col">

                                    <div class="input-group min_sal_inp">
                                        <span class="input-group-text" id="basic-addon1">Rs</span>
                                        <input type="text" name="min_salary" id="min_salary" class="form-control "
                                            placeholder="XXXXXX"  value="{{ $jobs->min_salary ? number_format($jobs->min_salary) : old('min_salary') }}" />
                                        @if ($errors->has('min_salary'))
                                            <div class="error text-danger">{{ $errors->first('min_salary') }}</div>
                                        @endif
                                    </div>
                                     <label id="minsalary-error" style="display: none;color:red">This field is required.</label>

                                </div>


                                <div class="col">
                                    <div class="input-group min_sal_inp">
                                        <span class="input-group-text" id="basic-addon1">Rs</span>
                                        <input type="text" name="max_salary" id="max_salary" class="form-control "
                                            placeholder="XXXXXX" value="{{ $jobs->max_salary ?  number_format($jobs->max_salary) : old('max_salary') }}"/>
                                        @if ($errors->has('max_salary'))
                                            <div class="error text-danger">{{ $errors->first('max_salary') }}</div>
                                        @endif
                                    </div>
                                    <label id="maxsalary-error" style="display: none;color:red">This field is required.</label>
                                </div>
                            </div>
                            <div class="row checkform mt-3">
                                @if (count($data['benefits']) > 0)
                                    @foreach ($data['benefits'] as $benefit)
                                        <div class="col form-check">
                                            <input class="form-check-input" type="checkbox" name="benefits[]"
                                                id="{{ $benefit->id }}" value="{{ $benefit->id }}" <?php 
                                            $data_id = [];
                                            if (isset($jobs->company_jod_detail)) {
                                                foreach ($jobs->company_jod_detail as $key => $company_jod_detail) {
                                                    if ($company_jod_detail->type ==='benefits' ) {
                                                        $data_id[] = $company_jod_detail->data_id;
                                                    }
                                                }
                                                if (in_array($benefit->id,$data_id))
                                                  {
                                                  echo 'checked';
                                                  }
                                                else
                                                  {
                                                   echo  '';
                                                  }
                                              }
                                        ?>/>
                                            <label class="form-check-label" htmlFor="{{ $benefit->id }}">
                                                {{ $benefit->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label" for="industry_domain">Other Requirements</label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Industry Domain
                            </label>
                            <select class="form-control custom-select2" name="industry_domain_id" id="industry_domain_id">
                                <option value="">Select Industry Domain</option>
                                @if (count($data['industry_domains']) > 0)
                                    @foreach ($data['industry_domains'] as $industry_domain)
                                        <option value="{{ $industry_domain->id }}" {{ $industry_domain->id ===$jobs->industry_domain_id ?'selected' :'' }}>{{ $industry_domain->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label id="industrydomainid-error" style="display: none;color:red">This field is required.</label>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="joining_preference"></label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Joining Preference
                            </label>
                            <select class="form-control custom-select2" name="joining_preference_id"
                                id="joining_preference">
                                <option value="">Select Joining Preference</option>
                                @if (count($data['joining_preferences']) > 0)
                                    @foreach ($data['joining_preferences'] as $joining_preference)
                                        <option value="{{ $joining_preference->id }}" {{ $joining_preference->id ===$jobs->joining_preference_id ?'selected' :'' }}>{{ $joining_preference->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('joining_preference_id'))
                                <div class="error text-danger">{{ $errors->first('joining_preference_id') }}</div>
                            @endif
                            <label id="joiningpreferenceid-error" style="display: none;color:red">This field is required.</label>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="job_duration_id"></label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Job Duration
                            </label>
                            <select class="form-control custom-select2" name="job_duration_id" id="job_duration_id">
                                <option value="">Select Job Duration</option>
                                @if (count($data['job_durations']) > 0)
                                    @foreach ($data['job_durations'] as $job_duration)
                                        <option value="{{ $job_duration->id }}" {{ $job_duration->id === $jobs->job_duration_id ?'selected' :'' }}>{{ $job_duration->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label id="jobdurationid-error" style="display: none;color:red">This field is required.</label>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-2">
                        <label class="col-md-2 form-control-label" for="is_telecommute"></label>

                        <div class='col-3'>
                            <label class="input-lebel-switch " htmlFor="">
                                Work from Home
                            </label>

                            <label class="switch mt-1 form-switch" style="position: absolute;">
                                <input type="checkbox" value="1" name="is_telecommute" class="switch-input form-check-input" {{$jobs->is_telecommute == 1 ? 'checked' :''}}/>
                                <span class="slider round switch-slider"></span>

                            </label>
                        </div>

                        <div class='col-3'>
                            <label class="input-lebel-switch " htmlFor="">
                                Travel Required
                            </label>
                            <label class="switch mt-1" style="position: absolute;">
                                <input type="checkbox" value="1" id="travel_required" name="is_travel_required" {{$jobs->is_travel_required == 1 ? 'checked' :''}}/>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class='col-4 travel_percentage' style="display: none;">
                            <input style="width: 90%" type="range" class="form-range" min="0" max="100"
                                step="25" id="customRange3" name="travel_percentage" value="{{$jobs->travel_percentage}}"></input>
                            <div class="range_label">
                                <div class="left_value">0%</div>
                                <div class="left_value">25%</div>
                                <div class="left_value">50%</div>
                                <div class="left_value">75%</div>
                                <div class="right_value">100%</div>
                            </div>
                            @if ($errors->has('travel_percentage'))
                                <div class="error text-danger">{{ $errors->first('travel_percentage') }}</div>
                            @endif
                        </div>
                    </div>

                </div>
                <!--col-->
            </div>
            <!--row-->
           <input type="hidden" name="status" value=""  id="job_type_save_action">

        </div>
        <!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.company.alljobs'), __('buttons.general.cancel')) }}
                </div>
                <!--col-->

                <div class="col text-right" style="text-align: end;">
                    {{-- {{ form_submit(__('buttons.general.crud.create')) }} --}}
                    <button type="button" class="btn btn-outline-primary" id="save-job"> Post Job</button>
                
                    <button type="button" class="btn btn-outline-success" id="save-draft">Save as Draft</button>
                </div>
                <!--col-->
            </div>
            <!--row-->
        </div>
        <!--card-footer-->
    </div>
    <!--card-->
    {{-- {{ html()->form()->close() }} --}}
    </form>


@endsection
{{-- @push('after-scripts') --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready (function () {  
     $('#required_skills').select2({
      tags: true,
      minimumInputLength: 1,
        ajax: {
            url: "{{ route('admin.fetch_skill') }}",
            processResults: function (data) {
                var myarray = new Array();
                $.each(data.data, function(key,value) {
                myarray.push({
                    "id": value.name,
                   "text": value.name

                })
                });
                 return {
                     results: myarray
                  };
                }
          }
    });
     $('#additional_skills').select2({
      tags: true,
      minimumInputLength: 1,
        ajax: {
            url: "{{ route('admin.fetch_skill') }}",
            processResults: function (data) {
                var myarray = new Array();
                $.each(data.data, function(key,value) {
                myarray.push({
                    "id": value.name,
                   "text": value.name

                })
                });
                 return {
                     results: myarray
                  };
                }
          }
    });
    $('#work_locations').select2({
      minimumInputLength: 3,
        ajax: {
            url: "{{ route('admin.fetch_location') }}",
            processResults: function (data) {
                var myarray = new Array();
                $.each(data.data, function(key,value) {
                myarray.push({
                    "id": value.id,
                   "text": value.name

                })
                });
                 return {
                     results: myarray
                  };
                }
          }
    });
    $('#max_salary').on('change',function(){
        var min_salary = $('#min_salary').val().replace(/,/g, '');
        var max_salary = $(this).val().replace(/,/g, '');
        if(parseInt(max_salary) > parseInt(min_salary) ){
        }else{
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
              });
              Toast.fire({
                icon: 'warning',
                title: "Maximum Salary can't be lesser than Minimum Salary!"
              })
               $(this).val('');
        }
    })
    $('#maximum_experience_required').on('change',function(){
        var minimum_experience_required = $('#minimum_experience_required').val();
        var maximum_experience_required = $(this).val();
        if(maximum_experience_required > minimum_experience_required ){
        }else{
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
              });
              Toast.fire({
                icon: 'warning',
                title: "Maximum Experience can't be lesser than Minimum Experience!"
              })
               $(this).val('');
        }
    })
    $("#title").blur(); 
    $("#company_id").focus(); 
    $('#title').on('change',function(){
        if($(this).val()){
            $('#title-error').css('display','none')
            return true;
        }else{
            $('#title-error').css('display','inline')
             return false;
        }
    })
    $('#company_id').on('change',function(){
        if($(this).val()){
            $('#companyid-error').css('display','none')
            return true;
        }else{
            $('#companyid-error').css('display','inline')
             return false;
        }
    })
    $('#user_id').on('change',function(){
        if($(this).val()){
            $('#companyuserid-error').css('display','none')
            return true;
        }else{
            $('#companyuserid-error').css('display','inline')
             return false;
        }
    })
    $('.salary_type').on('click',function(){
        if($(this).val()){
            $('#jobtypeid-error').css('display','none')
            return true;
        }else{
            $('#jobtypeid-error').css('display','inline')
             return false;
        }
    })
    $('#work_locations').on('change',function(){
        if($(this).val()){
            $('#worklocations-error').css('display','none')
            return true;
        }else{
            $('#worklocations-error').css('display','inline')
             return false;
        }
    })
    $('#required_skills').on('change',function(){
        if($(this).val()){
            $('#requiredskills-error').css('display','none')
            return true;
        }else{
            $('#requiredskills-error').css('display','inline')
             return false;
        }
    })
    $('#additional_skills').on('change',function(){
        if($(this).val()){
            $('#additionalskills-error').css('display','none')
            return true;
        }else{
            $('#additionalskills-error').css('display','inline')
             return false;
        }
    })
    $('.salary_type_id').on('click',function(){
        if($(this).val()){
            $('#salarytypeid-error').css('display','none')
             return true;
        }else{
            $('#salarytypeid-error').css('display','inline')
             return false;
        }
    })
    $('#joining_preference').on('change',function(){
        if($(this).val()){
            $('#joiningpreferenceid-error').css('display','none')
             return true;
        }else{
            $('#joiningpreferenceid-error').css('display','inline')
             return false;
        }
    })
    $('#industry_domain_id').on('change',function(){
        if($(this).val()){
            $('#industrydomainid-error').css('display','none')
             return true;
        }else{
            $('#industrydomainid-error').css('display','inline')
             return false;
        }
    })
    $('#job_duration_id').on('change',function(){
        if($(this).val()){
            $('#jobdurationid-error').css('display','none')
             return true;
        }else{
            $('#jobdurationid-error').css('display','inline')
             return false;
        }
    })
    var editor = CKEDITOR.replace( 'description' );
    // The "change" event is fired whenever a change is made in the editor.
    editor.on( 'change', function( evt ) {
        // getData() returns CKEditor's HTML content.
        if(evt.editor.getData().length > 0){
            $('#description-error').css('display','none')
             return true;
        }else{
            $('#description-error').css('display','inline')
             return false;
        }
    });
});  
    $(document).ready(function() {
        var is_travel_required = "{{$jobs->is_travel_required}}"
        if(is_travel_required == 1) {
            $('.travel_percentage').css("display", "block");
        }
        $('#company_id').on('change',function(){
            var company_id = ($(this).val());
             axios.post(`{{ route('admin.auth.company.job.get-company-users') }}`, {
                    'company_id': company_id
            })
            .then((resp) => {
                if (resp.data === 'error') {
                    // SwalMessage('Something went wrong. Try-again!','error');
                    $('#user_id').html('<option value=""></option>');
                }else {
                    if(typeof(resp.data[0]) != "undefined" && resp.data[0] !== null) {
                        // $('.add').css('display','block')
                        // $('.edit').css('display','none')
                        $('#user_id').html('<option value="">Select Company User</option>');

                        $.each(resp.data, function (key, value) {
                            var role = value?.roles[0]?.name;
                            if(value?.roles[0]?.name == 'company admin'){
                                role = 'Company Admin'
                            }else if(value?.roles[0]?.name == 'company user'){
                                role = 'Recruiter';
                            }
                            if(role === undefined){
                                 role = ''
                            }
                            $("#user_id").append('<option value="' + value
                                .id + '">' + value.first_name +' '+ value.last_name +' ('+ role +')'+ '</option>');
                        });
                    }

                };
            })
            .catch((err) => {
                // ld.stop();
                SwalMessage('Something went wrong. Try-again!', 'error');
                console.log(err);
                $(this).prop('disabled',false);
                $(this).removeClass('fade-btn');
            })
    })

        $('#save-draft').on('click',function(){
            $('#job_type').val('draft')
            $('#jobtypeid-error').css('display','none')
            $('#worklocations-error').css('display','none')
            $('#requiredskills-error').css('display','none')
            $('#additionalskills-error').css('display','none')
            $('#salarytypeid-error').css('display','none')
            $('#joiningpreferenceid-error').css('display','none')
            $('#description-error').css('display','none')
            $('#minimumexperiencerequired-error').css('display','none')
            $('#maximumexperiencerequired-error').css('display','none')
            $('#minsalary-error').css('display','none')
            $('#maxsalary-error').css('display','none')
            var status = $('#job_type').val('draft')
            var company_id = $('#company_id').val();
            var user_id = $('#user_id').val();
            var title = $('#title').val();
            if (company_id == '' && user_id ==null  && title == '') {
                $('#companyuserid-error').css('display','inline')
                $('#title-error').css('display','inline')
                $('#companyid-error').css('display','inline')
                $( "#company_id" ).trigger( "focus" );
                return false;
            }else if (company_id == '' && user_id ==null ) {
                $('#companyuserid-error').css('display','inline')
                $('#companyid-error').css('display','inline')
                $( "#company_id" ).trigger( "focus" );
                return false;
            }
            else if (company_id == ''   && title == '' ) {
                $('#title-error').css('display','inline')
                $('#companyid-error').css('display','inline')
                $( "#company_id" ).trigger( "focus" );
                return false;

            }
            else if (user_id ==null  && title == '') {
                $('#title-error').css('display','inline')
                $('#companyid-error').css('display','inline')
                $( "#company_id" ).trigger( "focus" );
                return false;
            }

            if (company_id == '') {
                $('#companyid-error').css('display','inline')
                $( "#company_id" ).trigger( "focus" );
                return false;
            }else{
                $('#companyid-error').css('display','none')
            }
            if (user_id === '') {
                $('#companyuserid-error').css('display','inline')
                $("#user_id" ).trigger( "focus" );
                 return false;
            }else{
                $('#companyuserid-error').css('display','none')
            }
            if (title == '') {
               $('#title-error').css('display','inline')
               $("#title" ).trigger( "focus" );
                return false;
            } else{
                $('#title-error').css('display','none')
            }
            $("#basic-form").validate().cancelSubmit = true
            $('#job_type_save_action').val('draft');
            $(".form-horizontal").submit();
        })
        $('#save-job').on('click',function(){
            var company_id = $('#company_id').val()
            var user_id = $('#user_id').val()
            var title = $('#title').val()
            var job_type_id = $('#job_type_id').val()
            var work_locations = $('#work_locations').val()
            var required_skills = $('#required_skills').val()
            var additional_skills = $('#additional_skills').val()
            var joining_preference = $('#joining_preference').val()
            var industry_domain_id = $('#industry_domain_id').val()
            var job_duration_id = $('#job_duration_id').val()
            var minimum_experience_required = $('#minimum_experience_required').val()
            var maximum_experience_required = $('#maximum_experience_required').val()
            if(company_id==''){
                $("#company_id").focus(); 
            }else if(user_id==''){
                $("#user_id").focus(); 
            }else if(title==''){
                $("#title").focus(); 
            }else if(job_type_id==''){
                $("#job_type_id").focus(); 
            }else if(work_locations==''){
                $("#work_locations").focus(); 
            }else if(required_skills==''){
                $("#required_skills").focus(); 
            }else if(additional_skills==''){
                $("#additional_skills").focus(); 
            }else if(joining_preference==''){
                $("#joining_preference").focus(); 
            }else if(minimum_experience_required==''){
                $("#minimum_experience_required").focus(); 
            }else if(maximum_experience_required==''){
                $("#maximum_experience_required").focus(); 
            }else if(industry_domain_id==''){
                $("#industry_domain_id").focus(); 
            }else if(job_duration_id==''){
                $("#job_duration_id").focus(); 
            }
            $('#job_type_save_action').val('published');
            $("#basic-form").validate({
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                ignore: [],
                rules: {
                    company_id: {
                        required:true 
                    },
                    user_id: {
                        required:true 
                    },
                    title: {
                        required:true 
                    },
                    job_type_id: {
                        required:true 
                    },
                    "work_locations[]": {
                        required:true 
                    },
                    "required_skills[]": {
                        required:true 
                    },
                    "additional_skills[]": {
                        required:true 
                    },
                    minimum_experience_required: {
                        required:true 
                    },
                    maximum_experience_required: {
                        required:true 
                    },
                    salary_type_id: {
                        required:true 
                    },
                    min_salary: {
                        required:true 
                    },
                    max_salary: {
                        required:true 
                    },
                    joining_preference_id: {
                        required:true 
                    },
                    industry_domain_id: {
                        required:true 
                    },
                    job_duration_id: {
                        required:true 
                    },
                    description:{
                         required: function() 
                        {
                         CKEDITOR.instances.description.updateElement();
                        }
                    },
                },
                onkeyup:function(element){
                    var name    = $(element).attr("id")
                    var value = $(element).val()
                    if(!name){
                        return
                    }

                    name = '#'+name.replace(/[^a-zA-Z ]/g, "")
                    if(value !==''){
                        $(name+'-error').css('display','none')
                    }else{
                        $(name+'-error').css('display','inline')
                    }
                },    
                errorPlacement: function (error, element) {
                    var name    = $(element).attr("name");
                    var value    = $(element).val();
                    console.log(name)

                    let id = '#'+name.replace(/[^a-zA-Z ]/g, "")

                    $(id+'-error').css('display','inline')
          
                },

            });
            
            $(".form-horizontal").submit();
        })

        $(".location-select2").select2();
        // Select locations
        $(".skill-select2").select2({
          tags: true
        });

        $('#required_skills').on('change',function(){
            var value = $('select#required_skills option:selected').val();
            var latest_value = $(this).closest('select').find('option').filter(':selected:last').val();
            var latest_text = $(this).closest('select').find('option').filter(':selected:last').text();
           axios.post(`{{ route('admin.auth.company.add-skills') }}`, {
                    'latest_value': latest_value,'latest_text':latest_text
            })
            .then((resp) => {
                if (resp.data === 'error') {
                    SwalMessage('Something went wrong. Try-again!','error');
                }else {

                };
            })
            .catch((err) => {
                // ld.stop();
                SwalMessage('Something went wrong. Try-again!', 'error');
                console.log(err);
            })
        })
        $('#additional_skills').on('change',function(){
            var value = $('select#additional_skills option:selected').val();
            var latest_value = $(this).closest('select').find('option').filter(':selected:last').val();
            var latest_text = $(this).closest('select').find('option').filter(':selected:last').text();
           axios.post(`{{ route('admin.auth.company.add-skills') }}`, {
                    'latest_value': latest_value,'latest_text':latest_text
            })
            .then((resp) => {
                if (resp.data === 'error') {
                    SwalMessage('Something went wrong. Try-again!','error');
                }else {

                };
            })
            .catch((err) => {
                // ld.stop();
                SwalMessage('Something went wrong. Try-again!', 'error');
                console.log(err);
            })
        })
        // Select Skills
        $(".custom-select2").select2();

        // $('.ckeditor').ckeditor();
        // CKEDITOR.replace('description');

        $("#travel_required").change(function() {
            if (this.checked) {
                $(".travel_percentage").show();
            } else {
                $(".travel_percentage").hide();
            }
        })

        $('.green-btn').on('click', function(e) {
            e.preventDefault();
            
            // let ld = Ladda.create(this);
            let title = $("#title").val();
            if (title.trim() == "") {
                SwalMessage('Please enter title', 'warning');
                return;
            }
            $(this).prop('disabled',true);
            $(this).addClass('fade-btn');
            const self = $(this);
            // ld.start();
            axios.post(`{{ route('admin.auth.company.job.description') }}`, {
                    'title': title
                })
                .then((resp) => {
                    if (resp.data == 'error') SwalMessage('Something went wrong. Try-again!',
                        'error');
                    else {
                        console.log('resp-->', resp);
                        CKEDITOR.instances['description'].setData(resp.data.description);
                    }
                    // ld.stop();
                    $(this).prop('disabled',false);
                    $(this).removeClass('fade-btn');
                })
                .catch((err) => {
                    // ld.stop();
                    SwalMessage('Something went wrong. Try-again!', 'error');
                    console.log(err);
                    $(this).prop('disabled',false);
                    $(this).removeClass('fade-btn');
                })

            // $(this).html('<i class="fa fa-eye-slash"></i>');
        });
         // var company_id ="{{$jobs->company_id}}";
         // if(company_id){
         //         axios.post(`{{ route('admin.auth.company.job.get-company-users') }}`, {
         //            'company_id': company_id
         //    })
         //    .then((resp) => {
         //        if (resp.data === 'error') {
         //            // SwalMessage('Something went wrong. Try-again!','error');
         //            $('#company_user_edit').html('<option value=""></option>');
         //        }else {
         //            if(typeof(resp.data[0]) != "undefined" && resp.data[0] !== null) {
         //                // $('.add').css('display','none')
         //                // $('.edit').css('display','block')
         //                $('#company_user').html('<option value="">Select Company User</option>');
         //                $.each(resp.data, function (key, value) {
         //                    var role = value?.roles[0]?.name;
         //                    if(value?.roles[0]?.name == 'company admin'){
         //                        role = 'Company Admin'
         //                    }else if(value?.roles[0]?.name == 'company user'){
         //                        role = 'Recruiter';
         //                    }
         //                    if(role === undefined){
         //                         role = ''
         //                    }
         //                    $("#company_user_edit").append('<option value="' + value
         //                        .id + '">' + value.first_name +' '+ value.last_name +' ('+ role +')'+ '</option>');
                            
         //                });
         //            }

         //        };
         //    })
         //    .catch((err) => {
         //        // ld.stop();
         //        SwalMessage('Something went wrong. Try-again!', 'error');
         //        console.log(err);
         //        $(this).prop('disabled',false);
         //        $(this).removeClass('fade-btn');
         //    })

         // }
        $('#min_salary').on('keyup',function(){
         this.value = this.value.replace(/[^0-9]/g, '');
          var num = $(this).val()
            num = addPeriod(num);
            $(this).val(num)
        })
        $('#max_salary').on('keyup',function(){
         this.value = this.value.replace(/[^0-9]/g, '');
          var num = $(this).val()
            num = addPeriod(num);
            $(this).val(num)
        })
        function addPeriod(num){
            var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
            if(str.indexOf(".") > 0) {
                parts = str.split(".");
                str = parts[0];
            }
            str = str.split("").reverse();
            for(var j = 0, len = str.length; j < len; j++) {
                if(str[j] != ",") {
                    output.push(str[j]);
                    if(i%3 == 0 && j < (len - 1)) {
                        output.push(",");
                    }
                    i++;
                }
            }
            formatted = output.reverse().join("");
            return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
        };
    });
</script>
{{-- @endpush --}}
