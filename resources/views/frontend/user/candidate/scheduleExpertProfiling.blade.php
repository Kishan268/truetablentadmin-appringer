@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | ' . __('navs.general.schedule_evaluation'))

@push('after-styles')
    {{-- <style type="text/css"> --}}
        {{-- .list-group-item{ --}}
            {{-- padding: 0.4rem; --}}
            {{-- border: none; --}}
        {{-- } --}}
    {{-- </style> --}}
@endpush

@section('content')
    {{-- @php dump($user->workProfile) @endphp --}}
    {{-- <h6>Personal Profiling</h6> --}}
    <schedule-evaluation :user='{{$logged_in_user}}'></schedule-evaluation>
@endsection
