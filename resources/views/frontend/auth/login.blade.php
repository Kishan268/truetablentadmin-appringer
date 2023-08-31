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
         .row.login-page {
            margin-top: 40% !important;
        }
       
    }
     @media only screen and (max-width: 900px) {
        .row.w-100.mx-0.auth-page {
            margin-bottom: 20%;
         }
        .row.login-page {
            margin-top: 40% !important;
        }
    }
     @media only screen and (max-width:1200px) {
     .row.w-100.mx-0.auth-page {
        margin-bottom: 20%;
     }
    }
    @media only screen and (max-width:2000px) {
     .row.w-100.mx-0.auth-page {
        margin-bottom: 20%;
     }
    }
    .row.login-page {
        margin-top: 20%;
    }
</style>

@section('content')
<div class="d-flex align-items-center justify-content-center">
  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-6 col-xl-6 mx-auto">
        <div class="row login-page">
          <div class="card">
              <div class="col-md-12">
                <div class="auth-form-wrapper px-3 py-3">
                  <h5 class="text-muted fw-normal mb-4 mt-2">Welcome back! Log in to your account.</h5>
                    {{ html()->form('POST', route('frontend.auth.login.post'))->open() }}

                    <div class="mb-3 col-md-12">
                      <label for="userEmail" class="form-label">Email address</label>
                        {{ html()->email('email')
                            ->class('form-control')
                            ->placeholder(__('validation.attributes.frontend.email'))
                            ->attribute('maxlength', 191)
                            ->required() }}

                            @if ($errors->has('email'))
                                <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                    </div>
                    <div class="mb-3 col-md-12">
                      <label for="userPassword" class="form-label">Password</label>
                       {{ html()->password('password')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.password'))
                                        ->required() }}
                        @if ($errors->has('password'))
                            <div class="error text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-check mb-3">
                      {{html()->checkbox('remember', true, 1)->class('form-check-input')}}
                      <label class="form-check-label" for="authCheck">
                        Remember me
                      </label>
                    </div>
                        @if(config('access.captcha.login'))
                            <div class="row">
                                <div class="col">
                                    @captcha
                                    {{ html()->hidden('captcha_status', 'true') }}
                                </div><!--col-->
                            </div><!--row-->e
                        @endif

                        <div class="row">
                        </div><!--row-->
                      {{ form_submit(__('labels.frontend.auth.login_button'), 'btn btn-primary') }}
                    </div>
                    {{ html()->form()->close() }}
                </div>
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
