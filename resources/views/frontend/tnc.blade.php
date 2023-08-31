@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('Terms & Conditions'))

@section('content')
    <section class="Material-contact-section section-padding section-dark" style="margin-top: 15vh;">
      <div class="container-fluid">
          <div class="row mb-5">
              <!-- Section Titile -->
              <div class="col-md-12 wow animated fadeInLeft" data-wow-delay=".2s">
                  <h1 class="section-title text-center">Terms & Conditions</h1>
              </div>
          </div>
          <div class="row">
              <!-- Section Titile -->
              <div class="col-md-12 contact-widget-section2 wow animated fadeInLeft" data-wow-delay=".2s">
                <ul>
                    <li>
                        <p class="lead">It is a long established fact that a reader will be distracted by the readable content of a page when
                            looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters,
                            as opposed to using Content.</p>
                    </li>
                    <li>
                        <p class="lead">It is a long established fact that a reader will be distracted by the readable content of a page
                            when
                            looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of
                            letters,
                            as opposed to using Content.</p>
                    </li>
                </ul>                
              </div>
          </div>
      </div>
    </section>
    
@endsection