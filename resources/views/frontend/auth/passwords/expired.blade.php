@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | ' . __('labels.frontend.auth.login_box_title'))

<style type="text/css">
     @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
    .fancy{
        font-family: 'Pacifico', cursive;
    }
    @media only screen and (max-width: 600px) {
        .demoText{
            font-size: 1.5rem!important;
        }
        .row.w-100.mx-0.auth-page {
            margin-bottom: 20%;
         }
    }
    @media only screen and (max-width: 900px) {
        .row.w-100.mx-0.auth-page {
            margin-bottom: 10%;
         }
    }
    @media only screen and (max-width:2000px) {
     .row.w-100.mx-0.auth-page {
        margin-bottom: 15%;
     }
    }
    @media only screen and (max-width:1200px) {
     .row.w-100.mx-0.auth-page {
        margin-bottom: 15%;
     }
    }
   /* @media only screen and (max-width:2000px) {
     .row.w-100.mx-0.auth-page {
        margin-bottom: 10%;
     }
    }*/
   
</style>

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">
  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-1 pe-md-0">
            {{-- <div class="auth-side-wrapper" style="background-image: url({{  asset('img/frontend/login-banner2.jpg') }})">
            </div> --}}
          </div>
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-5 py-5">
                
              {{ html()->form('PATCH', route('frontend.auth.password.expired.update'))->class('form-horizontal')->open() }}
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                {{ html()->label(__('validation.attributes.frontend.old_password'))->for('old_password') }}
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

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                {{ html()->label(__('validation.attributes.frontend.password'))->class('mt-2')->for('password') }}

                                {{ html()->password('password')
                                    ->class('form-control mt-2')
                                    ->placeholder(__('validation.attributes.frontend.password'))
                                    ->required() }}
                                     @if ($errors->has('password'))
                                        <div class="error text-danger">{{ $errors->first('password') }}</div>
                                    @endif
                            </div><!--form-group-->
                        </div><!--col-->
                    </div><!--row-->

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->class('mt-2')->for('password_confirmation') }}

                                {{ html()->password('password_confirmation')
                                    ->class('form-control  mt-2')
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
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
@push('after-scripts')
    @if(config('access.captcha.login'))
        @captchaScripts
    @endif
@endpush
