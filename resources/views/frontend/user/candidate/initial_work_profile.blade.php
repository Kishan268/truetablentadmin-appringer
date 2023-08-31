@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | ' . __('navs.general.home'))

@push('after-styles')
    <style type="text/css">
        .list-group-item {
            padding: 0.4rem;
            border: none;
        }

        .features {
            color: #fff;
            background-image: url("{{ asset('img/frontend/ttFeatures.jpg') }}");
            background-size: cover;
            background-position: center;
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
@endpush

@section('content')
    {{-- @php dump($user->workProfile) @endphp --}}
    <h4 class="text-center">Congratulations, {{ $user['name'] }} !</h4>
    <div class="row mt-3 mb-5">
        <div class="col">
            <div class="jumbotron p-5 m-3 features">
                <div class="container">
                    <h1 class="text-center">How
                        <img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"
                            style="max-width: 140px;"> help you make
                        next BIG career move...
                    </h1>
                    {{-- <p class="lead">
                        <ul>
                            <li>Step 1</li>
                            <li>Step 2</li>
                            <li>Step 3</li>
                            <li>Step 4</li>
                        </ul>
                    </p> --}}
                </div>
            </div>
        </div>
    </div>
    <!--row-->
    @include('frontend.user.candidate.create_work_profile')
@endsection
