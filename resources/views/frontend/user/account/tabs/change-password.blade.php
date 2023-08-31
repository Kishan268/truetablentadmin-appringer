@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.expired_password_box_title'))
<style type="text/css">
     @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
    .fancy{
        font-family: 'Pacifico', cursive;
    }
  
 
</style>

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-6 align-self-center" style="margin-top:6%">
            <div class="card">
               {{--  <div class="card-header">
                    <strong>
                        @lang('labels.frontend.passwords.expired_password_box_title')
                    </strong>
                </div> --}}<!--card-header-->
                @if ($errors->has('error'))
                    <div class="error text-danger">{{ $errors->first('error') }}</div>
                @endif
                <div class="card-body">
                    {{ html()->form('PATCH', route('frontend.auth.password.update'))->class('form-horizontal')->open() }}
                    {{-- {{ html()->form('PATCH', route('frontend.auth.password.expired.update'))->class('form-horizontal')->open() }} --}}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.old_password'))->class('mt-2')->for('old_password') }}

                                    {{ html()->password('old_password')
                                        ->class('form-control mt-2')
                                        ->placeholder(__('validation.attributes.frontend.old_password'))
                                        ->required() }}
                                        @if ($errors->has('old_password'))
                                            <div class="error text-danger">{{ $errors->first('old_password') }}</div>
                                        @endif
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.password'))->class('mt-2')->for('password') }}

                                    {{ html()->password('new_password')
                                        ->class('form-control mt-2')
                                        ->placeholder(__('validation.attributes.frontend.password'))
                                        ->required() }}
                                         @if ($errors->has('new_password'))
                                            <div class="error text-danger">{{ $errors->first('new_password') }}</div>
                                        @endif
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->class('mt-2')->for('password_confirmation') }}

                                    {{ html()->password('password_confirmation')
                                        ->class('form-control mt-2')
                                        ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                                        ->required() }}
                                        @if ($errors->has('password_confirmation'))
                                            <div class="error text-danger">{{ $errors->first('password_confirmation') }}</div>
                                        @endif
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row mt-4">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.passwords.update_password_button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                    {{ html()->form()->close() }}
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->
 </div><!-- row -->
@endsection
