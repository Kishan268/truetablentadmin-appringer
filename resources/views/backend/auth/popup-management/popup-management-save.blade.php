@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.popup_management'))

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
</style>
@endpush

@section('content')
{{ html()->modelForm('PATCH', route('admin.popup_management.save'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="row">
        <div class="col">
            <div class="card">
                <!--card-header-->
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='img'>Image upload</label>
                                <input type="file" class="form-control" name="img" value="" placeholder="Enter contact number" accept="image/png, image/gif, image/jpeg"/>
                            </div>
                            @if(isset($popup->img ))
                                <span class="mt-3">
                                    <a href="{{isset($popup->img ) ? App\Helpers\SiteHelper::getObjectUrl($popup->img) :''}}" target="_blank"><img src="{{isset($popup->img )? App\Helpers\SiteHelper::getObjectUrl($popup->img) :''}}" height="50" width="50"></a>
                                </span>
                            @endif
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='button1_text'>Button 1 Text</label>
                                <input type="text" class="form-control" name="button1_text" value="{{isset($popup->button1_text) ? $popup->button1_text :''}}" placeholder="Enter Button 1 Text" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='button1_action'>Button 1 Action</label>
                                <input type="text" class="form-control" name="button1_action" value="{{isset($popup->button1_action) ? $popup->button1_action :''}}" placeholder="Enter Button 1 Action" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='button2_text'>Button 2 Text</label>
                                <input name="button2_text" type="text" class="form-control" rows="4" placeholder="Enter Button 2 Text" value="{{isset($popup->button2_text) ? $popup->button2_text :''}}">
                            </div>
                        </div>
                         <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='button2_action'>Button 2 Action</label>
                                <input name="button2_action" type="text" class="form-control" rows="4" placeholder="Enter Button 2 Action" value="{{isset($popup->button2_action) ? $popup->button2_action :''}}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            <div class="form-group">
                                <label for='button2_action'>Duration (in seconds)</label>
                                <input name="duration" type="number" class="form-control" rows="4" placeholder="Duration (in seconds)" value="{{isset($popup->duration) ? $popup->duration :''}}">
                            </div>
                        </div>
                    </div>
                     <br/>
                  
                </div><!--card-body-->
                @canany('add_popups')
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
