@extends('backend.layouts.app')

@section('title', app_name() . ' | Featured Jobs Management')

@push('after-styles')
<style>
    td{
        text-transform: capitalize;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="card-title mb-0">
                    {{ __('Featured Jobs Management') }} 
                </h4>
            </div><!--col-->

            <div class="col-sm-4">
                <a href="{{ route('admin.auth.featured-jobs.create') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')">Add Featured Job</a>

                <a href="{{ route('admin.auth.featured-jobs.sequence') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')">Order Featured Jobs</a>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 25%;">Title</th>
                        <th style="width: 25%;">Company Name</th>
                        <th style="width: 25%;">Posted Date</th>
                        <th>@lang('labels.general.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td><a href="{{ env('FRONTEND_URL').'/job/details/'.$job->job_id }}" target="_blank">
                                    {{ 'TJ-'.str_pad($job->job_id, 5, '0', STR_PAD_LEFT) }}
                                </a></td>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->company_name }}</td>
                            <td>{{ date("d-m-Y", strtotime($job->created_at)) }}</td>
                            <td>

                                <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
                                    
                                    <a href="javascript:void(0)" name="confirm_item" jid="{{ $job->id }}" class="btn btn-danger updateStatus" data-toggle="tooltip" data-placement="top">@lang('buttons.general.crud.delete')</a>

                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection

@push('after-scripts')
<script>
    $('.updateStatus').on('click', function(){
        let ld = Ladda.create(this);
        let id = $(this).attr('jid');
        const self = $(this);
        ld.start();
        axios.post(`{{route("admin.auth.featured-jobs.delete")}}`, {'id': id})
             .then((resp) => {
                if(resp.data == 'error') toastr.error('Job cannot be deleted. Try-again!', 'System Error');
                else{
                    if(resp.data.opt == 1){
                        
                        toastr.success('Job deleted!', 'Success');
                        location.reload();
                    }else{
                        toastr.info('Job deleted!', 'Success');
                        location.reload();
                    }
                }
                ld.stop();
             })
             .catch((err) => {
                ld.stop();
                toastr.error('Job cannot be deleted. Try-again!', 'System Error');
                console.log(err);
             })
        
        // $(this).html('<i class="fas fa-eye-slash"></i>');
    });
</script>
@endpush
