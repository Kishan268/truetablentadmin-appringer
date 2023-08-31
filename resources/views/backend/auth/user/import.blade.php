@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.import'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.auth.user.importFile') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            @lang('labels.backend.access.users.management')
                            <small class="text-muted">@lang('labels.backend.access.users.import')</small>
                        </h4>
                    </div><!--col-->
                </div><!--row-->

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.bulk_user_upload'))->class('col-md-2 form-control-label')->for('bulk_user_upload') }}

                            <div class="col-md-10">
                                <input required type="file" name="file" accept=".xlsx">
                            </div><!--col-->
                        </div><!--form-group-->
                    </div><!--col-->
                </div><!--row-->

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.resume_zip_upload'))->class('col-md-2 form-control-label')->for('resume_zip_upload') }}
                            <div class="col-md-10">
                                <input type="file" name="zip" accept=".zip">
                            </div><!--col-->
                        </div><!--form-group-->
                    </div><!--col-->
                </div><!--row-->

            </div><!--card-body-->

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->

                    <div class="col">
                        
                    </div><!--col-->

                    <div class="col">
                        
                    </div><!--col-->

                    <div class="col text-right">
                        <a href="{{ route('admin.auth.user.resumes.delete') }}" style="color: black; background-color: white" class="btn  btn-sm pull-right" type="submit">Delete Uploaded Resumes</a>
                    </div><!--col-->

                    <div class="col text-right">
                        <button class="btn btn-success btn-sm pull-right" type="submit">Upload</button>
                    </div><!--col-->
                </div><!--row-->
                <div class="overflow-auto">
                    @if(isset($response) && count($response) > 0)
                        @foreach($response AS $resp)
                            {{ implode(", ",$resp) }} <br />
                        @endforeach
                    @endif
                </div>
            </div><!--card-footer-->
        </div><!--card-->
    {{ html()->form()->close() }}
@endsection
