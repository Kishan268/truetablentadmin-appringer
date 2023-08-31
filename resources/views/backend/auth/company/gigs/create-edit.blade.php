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
    <form class="form-horizontal" action="{{route('admin.auth.gigs.add-edit')}}" method="POST" id="basic-form">
        @csrf
    <div class="card add-job">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.gigs.management')
                        <small class="text-muted">@lang('labels.backend.access.gigs.create')</small>
                    </h4>
                </div>
                <!--col-->
            </div>
            <!--row-->

            <hr>
            <input type="hidden" name="id" value="{{ $companyGigs->id}}">
            <div class="row mt-4 mb-4">
                <div class="col">

                    <div class="form-group row mt-2">
                        <label class="col-md-2 form-control-label required" for="company_id">Company</label>

                        <div class="col-md-10">
                            <select class="form-control custom-select2" name="company_id" id="company_id">
                                @if (count($companies) > 0)
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{$companyGigs->company_id ===  $company->id ? 'selected' :''}}>{{ $company->name }}</option>
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
                        <label class="col-md-2 form-control-label required" for="user_id">Company User</label>
                        <div class="col-md-10 add">
                            @if(isset($companyGigs->user_id ))
                            <select id="user_id" class="form-control" name="user_id">
                                @if (count($usersWithRoles) > 0)
                                    @foreach ($usersWithRoles as $usersWithRole)
                                        <option value="{{ $usersWithRole->id }}" {{$companyGigs->user_id ===  $usersWithRole->id ? 'selected' :''}}>{{ @$usersWithRole->first_name .' '.@$usersWithRole->last_name ." (".@$usersWithRole->roles[0]->name .")"}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @else
                             <select id="user_id" class="form-control" name="user_id" required>
                            </select>
                            @endif
                            @if ($errors->has('user_id'))
                                <div class="error text-danger">{{ $errors->first('user_id') }}</div>
                            @endif
                            <label id="companyuserid-error" style="display: none;color:red">This field is required.</label>

                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 form-control-label required" for="title">Title and Description</label>

                        <div class="col-md-8">
                            <input class="form-control" type="text" name="title" id="title" placeholder="Gig Title"
                                maxlength="191" autofocus="" value="{{ $companyGigs->title ? $companyGigs->title : old('title') }}">
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
                            <textarea id="description" name="description" rows="7" class="form-control" placeholder="Description">{{ $companyGigs->description ? $companyGigs->description : old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <div class="error text-danger">{{ $errors->first('description') }}</div>
                            @endif
                            <label id="description-error" style="display: none;color:red">This field is required.</label>
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label required" for="gig_type_id">Gig Type</label>

                        <div class="col-md-10">
                            @if (count($data['gig_types']) > 0)
                                @foreach ($data['gig_types'] as $gig_type)
                                    <div class="form-check radio-check">
                                        <input name="gig_type_id" id="gig_type{{ $gig_type->id }}" class="form-check-input"
                                            type="radio" value="{{ $gig_type->id }}" {{$companyGigs->gig_type_id === $gig_type->id  ? 'checked' :''}}/>
                                        <label class="form-check-label" htmlFor="gig_type{{ $gig_type->id }}">
                                            {{ $gig_type->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($errors->has('gig_type_id'))
                                    <div class="error text-danger">{{ $errors->first('gig_type_id') }}</div>
                                @endif
                                <label id="gigtypeid-error" style="display: none;color:red">This field is required.</label>
                            @endif
                        </div>
                        <!--col-->
                    </div>
                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label " for="engagement_mode">Engagement Mode</label>
                        <div class="col-md-10">
                            @if (count($data['engagement_mode']) > 0)
                                @foreach ($data['engagement_mode'] as $engagement_mode)
                                    <div class="form-check radio-check">
                                        <input name="engagement_mode_id" id="engagement_mode{{ $engagement_mode->id }}" class="form-check-input"
                                            type="radio" value="{{ $engagement_mode->id }}" {{$companyGigs->engagement_mode_id === $engagement_mode->id  ? 'checked' :''}}/>
                                        <label class="form-check-label" htmlFor="engagement_mode{{ $engagement_mode->id }}">
                                            {{ $engagement_mode->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($errors->has('gig_type_id'))
                                    <div class="error text-danger">{{ $errors->first('gig_type_id') }}</div>
                                @endif
                                <label id="gigtypeid-error" style="display: none;color:red">This field is required.</label>
                            @endif
                        </div>
                        <!--col-->
                    </div>

                    <div class="form-group row  mt-4">
                        <label class="col-md-2 form-control-label required" for="location">Gig Location</label>

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
                        <label class="col-md-2 form-control-label required" for="required_skills">Skills and Budget</label>

                        <div class="col-md-10">
                            <label class="input-lebel required" htmlFor="">
                                Skills
                            </label>
                            <select class="form-control" multiple name="required_skills[]"  id="required_skills">
                                @if(isset($companyGigs->details))
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
                                Budget
                            </label>
                            <div class="row  mt-2">
                                <div class="col">

                                    <div class="input-group min_sal_inp">
                                        <span class="input-group-text" id="basic-addon1">Rs</span>
                                        <input type="text" name="min_budget" id="min_budget" class="form-control "
                                            placeholder="XXXXXX"  value="{{ $companyGigs->min_budget ? number_format($companyGigs->min_budget) : old('min_budget') }}" />
                                        @if ($errors->has('min_budget'))
                                            <div class="error text-danger">{{ $errors->first('min_budget') }}</div>
                                        @endif
                                    </div>
                                     <label id="minsalary-error" style="display: none;color:red">This field is required.</label>

                                </div>


                                <div class="col">
                                    <div class="input-group min_sal_inp">
                                        <span class="input-group-text" id="basic-addon1">Rs</span>
                                        <input type="text" name="max_budget" id="max_budget" class="form-control "
                                            placeholder="XXXXXX" value="{{ $companyGigs->max_budget ?  number_format($companyGigs->max_budget) : old('max_budget') }}"/>
                                        @if ($errors->has('max_budget'))
                                            <div class="error text-danger">{{ $errors->first('max_budget') }}</div>
                                        @endif
                                    </div>
                                    <label id="maxsalary-error" style="display: none;color:red">This field is required.</label>
                                </div>
                            </div>
                         
                        </div>
                        <!--col-->
                    </div>


                </div>
                <!--col-->
            </div>
            <!--row-->
           <input type="hidden" name="status" value=""  id="gig_type_save_action">

        </div>
        <!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">

                <div class="col text-right" style="text-align: end;">
                    {{-- {{ form_submit(__('buttons.general.crud.create')) }} --}}
                    <button type="button" class="btn btn-outline-primary" id="save-job"> Post Gig</button>
                
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
    $('#max_budget').on('change',function(){
        var min_budget = $('#min_budget').val().replace(/,/g, '');
        var max_budget = $(this).val().replace(/,/g, '');
        if(parseInt(max_budget) > parseInt(min_budget) ){
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
                title: "Maximum Budget can't be lesser than Minimum Budget!"
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
    $('.gig_type_id').on('click',function(){
        if($(this).val()){
            $('#gigtypeid-error').css('display','none')
            return true;
        }else{
            $('#gigtypeid-error').css('display','inline')
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
        var is_travel_required = "{{$companyGigs->is_travel_required}}"
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
            $('#gig_type').val('draft')
            $('#gigtypeid-error').css('display','none')
            $('#worklocations-error').css('display','none')
            $('#requiredskills-error').css('display','none')
            $('#salarytypeid-error').css('display','none')
            $('#joiningpreferenceid-error').css('display','none')
            $('#description-error').css('display','none')
            $('#minimumexperiencerequired-error').css('display','none')
            $('#maximumexperiencerequired-error').css('display','none')
            $('#minsalary-error').css('display','none')
            $('#maxsalary-error').css('display','none')
            var status = $('#gig_type').val('draft')
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
            $('#gig_type_save_action').val('draft');
            $(".form-horizontal").submit();
        })
        $('#save-job').on('click',function(){
            var company_id = $('#company_id').val()
            var user_id = $('#user_id').val()
            var title = $('#title').val()
            var gig_type_id = $('#gig_type_id').val()
            var work_locations = $('#work_locations').val()
            var required_skills = $('#required_skills').val()
            
            if(company_id==''){
                $("#company_id").focus(); 
            }else if(user_id==''){
                $("#user_id").focus(); 
            }else if(title==''){
                $("#title").focus(); 
            }else if(gig_type_id==''){
                $("#gig_type_id").focus(); 
            }else if(work_locations==''){
                $("#work_locations").focus(); 
            }else if(required_skills==''){
                $("#required_skills").focus(); 
            }
            $('#gig_type_save_action').val('published');
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
                    gig_type_id: {
                        required:true 
                    },
                    "work_locations[]": {
                        required:true 
                    },
                    "required_skills[]": {
                        required:true 
                    },
                   
                    min_budget: {
                        required:true 
                    },
                    max_budget: {
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
                    let id = '#'+name.replace(/[^a-zA-Z ]/g, "")

                    console.log(id+'-error')
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
        
        $('#min_budget').on('keyup',function(){
         this.value = this.value.replace(/[^0-9]/g, '');
          var num = $(this).val()
            num = addPeriod(num);
            $(this).val(num)
        })
        $('#max_budget').on('keyup',function(){
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
