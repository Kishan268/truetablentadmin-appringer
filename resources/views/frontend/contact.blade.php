@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.contact.box_title'))

<style type="text/css">
    /* body{
        background-image: url('{{asset('img/frontend/contact.jpg')}}');
        background-size: cover;
        background-position: center;
        color: #fff;
    } */
</style>
@section('content')
    <section class="Material-contact-section section-padding section-dark" style="margin-top: 15vh;">
      <div class="container-fluid">
          <div class="row mb-5">
              <!-- Section Titile -->
              <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
                  <h1 class="section-title text-center">Contact Us</h1>
              </div>
          </div>
          <div class="row">
              <!-- Section Titile -->
              <div class="col-md-6 contact-widget-section2 wow animated fadeInLeft" data-wow-delay=".2s">
                @if($data->contact_text != null)
                <p class="lead">{{$data->contact_text}}</p>
                @endif

                @if($data->company_name != null)
                <div class="find-widget">
                    <p class="lead">Company:  <a href="javascript:void(0);">{{$data->company_name}}</a></p>
                </div>
                @endif

                @if($data->company_address != null)
                <div class="find-widget">
                    <p class="lead">Address: <a href="javascript:void(0);">{{$data->company_address}}</a></p>
                </div>
                @endif

                @if($data->company_phone != null)
                <div class="find-widget">
                    <p class="lead">Phone:  <a href="tel:{{$data->company_phone}}">{{$data->company_phone}}</a></p>
                </div>
                @endif

                @if($data->company_website != null)
                <div class="find-widget">
                    <p class="lead">Website: <a href="{{ substr($data->company_website, 0, 4) == 'http' ? $data->company_website : 'http://'.$data->company_website}}">{{$data->company_website}}</a></p>
                </div>
                @endif

                @if($data->company_email != null)
                <div class="find-widget">
                    <p class="lead">Email: <a href="mailto:{{$data->company_email}}">{{$data->company_email}}</a>
                </div>
                @endif
              </div>
              <!-- contact form -->
              <div class="col-md-6 wow animated fadeInRight" data-wow-delay=".2s">
                 {{ html()->form('POST', route('frontend.contact.send'))->open() }}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.name'))->for('name') }}

                                    {{ html()->text('name', optional(auth()->user())->name)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.name'))
                                        ->attribute('maxlength', 191)
                                        ->required()
                                        ->autofocus() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                                    {{ html()->email('email', optional(auth()->user())->email)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.phone'))->for('phone') }}

                                    {{ html()->text('phone')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.phone'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.message'))->for('message') }}

                                    {{ html()->textarea('message')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.message'))
                                        ->attribute('rows', 3)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        @if(config('access.captcha.contact'))
                            <div class="row">
                                <div class="col">
                                    @captcha
                                    {{ html()->hidden('captcha_status', 'true') }}
                                </div><!--col-->
                            </div><!--row-->
                        @endif

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    <button class="btn btn-outline-secondary" type="submit"> Send</button>
                                    {{-- {{ form_submit(__('labels.frontend.contact.button')) }} --}}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
              </div>
          </div>
      </div>
    </section>
    
@endsection

@push('after-scripts')
    @if(config('access.captcha.contact'))
        @captchaScripts
    @endif
@endpush