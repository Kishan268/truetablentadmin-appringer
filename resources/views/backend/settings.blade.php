@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.settings'))

@push('after-styles')
<style>
    .stats{
        font-size: 4rem;
    }
    .redirectTo{
        cursor: pointer;
    }
</style>
@endpush

@section('content')
{{ html()->modelForm('PATCH', route('admin.system_settings.save'))->class('form-horizontal')->open() }}
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.settings')</strong>
                </div><!--card-header-->
                <div class="card-body">
                    <b>TT Cash Charging</b>
                    <hr />
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='profile_view_ttcash'><i class="fab fa-money-alt"></i> Amount for viewing Candidate's Work-Profile</label>
                                <input type="number" class="form-control" name="profile_view_ttcash" min=0 value="{{$SystemSettings['profile_view_ttcash']}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='evaluation_view_ttcash'><i class="fab fa-money-alt"></i> Amount for viewing Candidate's Evaluation Section</label>
                                <input type="number" class="form-control" name="evaluation_view_ttcash" min=0 value="{{$SystemSettings['evaluation_view_ttcash']}}" />
                            </div>
                        </div>
                    </div>

                    <br/>
                    <b>Social Links</b><hr/>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for='fb'></i> Facebook</label>
                                <input type="text" class="form-control" name="fb" placeholder="Enter facebook page URL" value="{{$SystemSettings['fb']}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for='fb'> Instagram</label>
                                <input type="text" class="form-control" name="instagram" placeholder="Enter instagram page URL" value="{{$SystemSettings['instagram']}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for='twitter'> Twitter</label>
                                <input type="text" class="form-control" name="twitter" placeholder="Enter twitter URL" value="{{$SystemSettings['twitter']}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for='linkedin'> LinkedIn</label>
                                <input type="text" class="form-control" name="linkedin" placeholder="Enter linkedIn URL" value="{{$SystemSettings['linkedin']}}" />
                            </div>
                        </div>
                    </div>
                    
                    <br/>
                    <b>Contact Information</b><hr/>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for='contact_text'>Contact text</label>
                                <textarea name="contact_text" class="form-control" rows="4" placeholder="Enter contact text">{{$SystemSettings['contact_text']}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='company_name'>Company Name</label>
                                <input type="text" class="form-control" name="company_name" placeholder="Enter company name" value="{{$SystemSettings['company_name']}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='company_phone'>Contact Number</label>
                                <input type="text" class="form-control" name="company_phone" value="{{$SystemSettings['company_phone']}}" placeholder="Enter contact number" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='company_website'>Company Website</label>
                                <input type="text" class="form-control" name="company_website" value="{{$SystemSettings['company_website']}}" placeholder="Enter company website address" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for='company_email'>Company Email</label>
                                <input type="email" class="form-control" name="company_email" value="{{$SystemSettings['company_email']}}" placeholder="Enter company email address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for='company_address'>Company Address</label>
                                <textarea name="company_address" class="form-control" rows="2" placeholder="Enter company address">{{$SystemSettings['company_address']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div><!--card-body-->
                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                        </div>
                        <!--col-->
                
                        <div class="col text-right">
                            {{ form_submit(__('buttons.general.save')) }}
                        </div>
                        <!--row-->
                    </div>
                    <!--row-->
                </div>
                <!--card-footer-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
{{ html()->closeModelForm() }}
@endsection