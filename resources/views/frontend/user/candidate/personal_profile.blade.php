@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | ' . __('navs.general.personal_profiling'))

@push('after-styles')
    {{-- <style type="text/css"> --}}
        {{-- .list-group-item{ --}}
            {{-- padding: 0.4rem; --}}
            {{-- border: none; --}}
        {{-- } --}}
    {{-- </style> --}}
@endpush
@section('page_title', 'Personal Profiling')
@section('content')
    {{-- @php dump($user->workProfile) @endphp --}}
    {{-- <h6>Personal Profiling</h6> --}}
    <personal-profile :user='{{$logged_in_user}}' csrf='{{ csrf_token() }}'></personal-profile>
@endsection
