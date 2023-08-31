@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | Posted Jobs')

@push('after-styles')
    <style type="text/css">
        .list-group-item{
            padding: 0.4rem;
            border: none;
        }
        .features{
            /*color: #fff;*/
            /*background-image: url("{{asset('img/frontend/image.jpg')}}");*/
            /*background-size: cover;*/
            /*background-position: center;*/
        }
        .viewActive{
            color: #007bff;
        }
        .switch { 
            position : relative ;
            display : inline-block;
            width : 60px;
            height : 30px;
            background-color: #eee;
            border-radius: 40px;
        }
        .switch::after {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: white;
            top: 2px;
            left: 1px;
            transition: all 0.4s;
        }
        .checkbox:checked + .switch::after {
            left : 30px; 
        }
        .checkbox:checked + .switch {
            background-color: #7983ff;
        }
        .checkbox { 
            display : none;
        }
        .form-inline label{
            justify-content: flex-start;
        }
    </style>
@endpush

@section('content')
    {{-- @php dump($user->workProfile) @endphp --}}
    <jobs csrf='{{ csrf_token() }}' :user='{{$logged_in_user}}'></jobs>
@endsection