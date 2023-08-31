@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.footer_content'))

@push('after-styles')
<style>
    .stats{
        font-size: 4rem;
    }
    .redirectTo{
        cursor: pointer;
    } 
    .select2{
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        
/*         padding: 1px 17px; */
    }    
     .my-error-class {
       color: red;
    } 
    
     
    .nav-links {
        margin-top: 32px;
    }
    .nav-links1 {
        margin-top: 16px;
    }
    @media screen and ( max-width: 767px ){
        .nav-links {
            margin-top: 0px;
        }
        .nav-links1 {
            margin-top: 0px;
        }
    }                                                                                                                                
</style>
@endpush
                                                                                                                                                                                                                                                                                                                                                         
@section('content')
{{ html()->modelForm('PATCH', route('admin.footer_content.save'))->class('form-horizontal')->id('basic-form')->open() }}
    <div class="row">
        <div class="col">
            <div class="card">
                <!--card-header-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='company_phone form-control-label required' class="form-control-label required">Contact Number</label>
                                <input type="text" class="form-control" name="company_phone" value="{{isset($SystemSettings->company_phone )? $SystemSettings->company_phone :''}}" placeholder="Enter contact number" required />
                                 @if ($errors->has('company_phone'))
                                    <div class="error text-danger">{{ $errors->first('company_phone') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='company_website form-control-label required' class="form-control-label required">Company Website</label>
                                <input type="text" class="form-control" name="company_website" value="{{isset($SystemSettings->company_website) ? $SystemSettings->company_website :''}}" placeholder="Enter company website address" />
                                 @if ($errors->has('company_website'))
                                    <div class="error text-danger">{{ $errors->first('company_website') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='company_email form-control-label required' class="form-control-label required">Company Email</label>
                                <input type="email" class="form-control" name="company_email" value="{{isset($SystemSettings->company_email) ? $SystemSettings->company_email :''}}" placeholder="Enter company email address" />
                                 @if ($errors->has('company_email'))
                                    <div class="error text-danger">{{ $errors->first('company_email') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-6  mt-3">
                            <div class="form-group">
                                <label for='company_address form-control-label required' class="form-control-label required">Company Address</label>
                                <textarea name="company_address" class="form-control" rows="4" placeholder="Enter company address">{{isset($SystemSettings->company_address) ? $SystemSettings->company_address :''}}</textarea>
                                 @if ($errors->has('company_address'))
                                    <div class="error text-danger">{{ $errors->first('company_address') }}</div>
                                @endif
                            </div>
                        </div>
                         <div class="col-12 col-md-6  mt-3">
                            <div class="form-group">
                                <label for='contact_text form-control-label required' class="form-control-label required">Contact text</label>
                                <textarea name="contact_text" class="form-control" rows="4" placeholder="Enter contact text">{{isset($SystemSettings->contact_text) ? $SystemSettings->contact_text :''}}</textarea>
                                 @if ($errors->has('contact_text'))
                                    <div class="error text-danger">{{ $errors->first('contact_text') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                     <br/>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <b>Navigate Links &nbsp;</b>
                            <span class="add_button btn btn-outline-primary btn-xs"  > {{__('buttons.general.add-more') }}  </span>
                        </div>
                    </div>
                    <div class="field_wrapper">
                        @if(count($navigations )>0)
                            @foreach($navigations as $key => $navigation)
                                <div class="row ">
                                    <div class="col-md-5  mt-3 nav{{$key}}">
                                        <div class="form-group">
                                            <label for='nav_name form-control-label required' class="form-control-label required">Text</label>
                                            <input type="text" class="form-control" name="nav_name[]" value="{{isset($navigation->text )? $navigation->text :''}}" placeholder="Enter navigation name" />
                                             @if ($errors->has('nav_name'))
                                                <div class="error text-danger">{{ $errors->first('nav_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-5  mt-3  nav{{$key}}">
                                        <div class="form-group">
                                            <label for='nav_link form-control-label required' class="form-control-label required">Link</label>
                                            <input type="text" class="form-control" name="nav_link[]" value="{{isset($navigation->value) ? $navigation->value :''}}" placeholder="/home" />
                                             @if ($errors->has('nav_link'))
                                                <div class="error text-danger">{{ $errors->first('nav_link') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 nav-links nav{{$key}}">
                                          <div class="col-md-2 nav{{$key}}">
                                            <a href="javascript:void(0);" class="remove_button pull-righ" data-id="{{$key}}"><button class="btn btn-danger mt-2 btn-xs"><i class="fa fa-minus"></i></button></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mt-3 nav_new0">
                                <div class="col-md-5  mt-3">
                                    <div class="form-group">
                                        <label for='nav_name form-control-label required' class="form-control-label required">Text</label>
                                        <input type="text" class="form-control" name="nav_name[]" value="{{isset($navigation->text )? $navigation->text :''}}" placeholder="Enter navigation name" />
                                         @if ($errors->has('nav_name'))
                                            <div class="error text-danger">{{ $errors->first('nav_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5  mt-3">
                                    <div class="form-group">
                                        <label for='nav_link form-control-label required' class="form-control-label required">Link</label>
                                        <input type="text" class="form-control" name="nav_link[]" value="{{isset($navigation->value) ? $navigation->value :''}}" placeholder="/home" />
                                         @if ($errors->has('nav_link'))
                                            <div class="error text-danger">{{ $errors->first('nav_link') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 nav-links1">
                                    <div class="form-group">
                                        <div class="col-md-2 mt-3 nav_new0"><a href="javascript:void(0);" class="remove_button1" data-id="0"><button class="btn btn-danger mt-2 btn-xs"><i class="fa fa-minus"></i></button></a></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                     <br/>
                    <div class="row">
                       <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='required_skills form-control-label required' class="form-control-label required"></i> JOBS BY SKILLS</label>
                                <select class="js-data-example-ajax form-control" multiple name="required_skills[]"
                                        id="required_skills">
                                    @if(isset($job_by_skills))
                                        @foreach($job_by_skills as $job_by_skill)
                                         <option value="{{$job_by_skill->value}}" selected="selected">{{$job_by_skill->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('required_skills'))
                                    <div class="error text-danger">{{ $errors->first('required_skills') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='work_locations form-control-label required' class="form-control-label required"></i> JOBS BY LOCATIONS</label>
                                 <select class="form-control" multiple name="work_locations[]"
                                        id="work_locations">
                                    @if(isset($job_by_locations))
                                        @foreach($job_by_locations as $work_location)
                                         <option value="{{$work_location->value}}" selected="selected">{{$work_location->text}}</option>
                                        @endforeach
                                    @endif

                                </select>
                                 @if ($errors->has('work_locations'))
                                    <div class="error text-danger">{{ $errors->first('work_locations') }}</div>
                                @endif
                            </div>
                        </div>
                         <div class="col-12 col-md-4  mt-3">
                            <div class="form-group">
                                <label for='industry_domain form-control-label required' class="form-control-label required"></i> JOBS BY INDUSTRIES</label>
                                 <select class="form-control " name="industry_domain_id[]" id="industry_domain" multiple="multiple">
                                    <option value="">Select Industry Domain</option>
                                    @if (count($data['industry_domains']) > 0)
                                        @foreach ($data['industry_domains'] as $industry_domain)
                                            <option value="{{ $industry_domain->id }}" <?php if (in_array($industry_domain->id,$job_by_industries)) {
                                                echo "selected";
                                            }?>>{{ $industry_domain->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                 @if ($errors->has('industry_domain_id'))
                                    <div class="error text-danger">{{ $errors->first('industry_domain_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br/>
                    <b>Social Links</b>
                    <div class="row">
                        <div class="col-12 col-md-3  mt-3">
                            <div class="form-group">
                                <label for='fb'></i> Facebook</label>
                                <input type="text" class="form-control" name="fb" placeholder="Enter facebook page URL" value="{{isset($SystemSettings->fb) ?$SystemSettings->fb:''}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3  mt-3">
                            <div class="form-group">
                                <label for='instagram'> Instagram</label>
                                <input type="text" class="form-control" name="instagram" placeholder="Enter instagram page URL" value="{{isset($SystemSettings->instagram )?$SystemSettings->instagram :''}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3  mt-3">
                            <div class="form-group">
                                <label for='twitter'> Twitter</label>
                                <input type="text" class="form-control" name="twitter" placeholder="Enter twitter URL" value="{{isset($SystemSettings->twitter) ?$SystemSettings->twitter:''}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3  mt-3">
                            <div class="form-group">
                                <label for='linkedin'> LinkedIn</label>
                                <input type="text" class="form-control" name="linkedin" placeholder="Enter linkedIn URL" value="{{isset($SystemSettings->linkedin) ?$SystemSettings->linkedin:''}}" />
                            </div>
                        </div>
                    </div>
                </div><!--card-body-->
                @canany('add_footer_content')
                    <div class="card-footer">
                        <div class="row">
                            <div class="col text-right">
                                {{ form_submit(__('buttons.general.save')) }}
                            </div>
                            <!--row-->
                        </div>
                        <!--row-->
                    </div>
                @endcanany
                <!--card-footer-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
{{ html()->closeModelForm() }}
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
     $(document).ready(function(){
        $("#work_locations").select2();
        $("#industry_domain").select2();
        // Select locations
        $("#required_skills").select2({
          tags: true
        });
        $("#industry_domain").on("select2:select", function (evt) {
          var element = evt.params.data.element;
          var $element = $(element);
          
          $element.detach();
          $(this).append($element);
          $(this).trigger("change");
        });
          var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var x = 1; //Initial field counter is 1
            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){ 
                    x++; //Increment field counter

                    $(wrapper).append( '<div><div class="row mt-3 nav_new'+x+'"><div class="col-12 col-md-5 nav_new'+x+'"><div class="form-group"><label for="nav_name " class="form-control-label required">Text</label><input type="text" class="form-control" name="nav_name[]"  placeholder="Enter navigation name" required/> @if ($errors->has('nav_name'))<div class="error text-danger">{{ $errors->first('nav_name') }}</div>@endif</div></div> <div class="col-12 col-md-5 nav_new'+x+'"><div class="form-group"><label for="nav_link " class="form-control-label required">Link</label><input type="text" class="form-control" name="nav_link[]" placeholder="/home" required/>@if ($errors->has('nav_link'))<div class="error text-danger">{{ $errors->first('nav_link') }}</div>@endif</div></div><div class="col-md-2 nav-links1 nav_new'+x+'"><a href="javascript:void(0);" class="remove_button1" data-id="'+x+'"><button class="btn btn-danger mt-2 btn-xs"><i class="fa fa-minus"></i></button></a></div></div>'); //New input field html ); //Add field html
                }
                 
            });
            
            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $('.nav'+$(this).attr('data-id')).remove()
                // $(this).parent().remove();
                x--; //Decrement field counter
            });
            $(wrapper).on('click', '.remove_button1', function(e){
                e.preventDefault();
                $('.nav_new'+$(this).attr('data-id')).remove()
                // $(this).parent().remove();
                x--; //Decrement field counter
            });


        $('#basic-form').on('click',function(){
            $(this).validate({
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                ignore: [],
                rules: {
                    company_phone: {
                        required:true
                    },
                    company_website: {
                        required:true 
                    },
                    company_email: {
                        required:true 
                    },
                     company_address: {
                        required:true 
                    },
                    contact_text: {
                        required:true 
                    },
                    "nav_name[]": {
                        required:true 
                    },
                    "nav_link[]": {
                        required:true 
                    },
                    "required_skills[]": {
                        required:true 
                    }, 
                    "work_locations[]": {
                        required:true 
                    }, 
                    "industry_domain_id[]": {
                        required:true 
                    }
                },  

            });
        });

        $('#required_skills').select2({
          minimumInputLength: 1,
            ajax: {
                url: "{{ route('admin.fetch_skill') }}",
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
     })
</script>