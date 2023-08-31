@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@push('after-styles')
    <style>
        .stats {
            font-size: 4rem;
        }

        .redirectTo {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</strong>
                </div>
                <!--card-header-->
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-6 redirectTo" to="{{ route('admin.auth.company.payments') }}"
                            title='Click to view Details'>
                            <span>Total Payments</span>
                            <div class="alert alert-info text-center" role="alert">
                                <span class="stats">
                                    <i class="fas fa-money-bill-alt mx-3"></i>
                                    {{getSystemConfig('currency').' '.$data['payments']}}
                                </span>
                            </div>
                        </div>
                        <div class="col col-md-6 redirectTo" to="{{ route('admin.auth.allcompany.index') }}"
                            title='Click to view Details'>
                            <span>Total Companies</span>
                            <div class="alert alert-success text-center" role="alert">
                                <span class="stats">
                                    {{-- <i class="fas fa-building mx-3"></i> --}}
                                    {{ $data['companies'] }}
                                </span>
                            </div>
                        </div>
                        <div class="col col-md-6 redirectTo" to="{{ route('admin.auth.user.index') }}"
                            title='Click to view Details'>
                            <span>Total Candidates</span>
                            <div class="alert alert-warning text-center" role="alert">
                                <span class="stats">
                                    {{-- <i class="fas fa-users mx-3"></i> --}}
                                    {{ $data['candidates'] }}
                                </span>
                            </div>
                        </div>
                        <div class="col col-md-6 redirectTo" to="{{ route('admin.auth.company.alljobs') }}"
                            title='Click to view Details'>
                            <span>Total Jobs</span>
                            <div class="alert alert-danger text-center" role="alert">
                                <span class="stats">
                                    {{-- <i class="fas fa-briefcase mx-3"></i> --}}
                                    {{ $data['jobs'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- {!! __('strings.backend.welcome') !!} --}}
                </div>
                <!--card-body-->
            </div>
            <!--card-->
        </div>
        <!--col-->
    </div>
    <!--row-->
@endsection
@push('after-scripts')
    <script>
        $(function() {
            $('.redirectTo').on('click', function() {
                console.log($(this).attr('to'));
                window.location.assign($(this).attr('to'));
            })
        });
    </script>
@endpush
