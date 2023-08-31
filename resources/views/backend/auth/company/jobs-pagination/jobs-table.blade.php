 @foreach($jobs as $job)
    <tr>
        <td><a href="{{ env('FRONTEND_URL').'/job/details/'.$job->id.'/'.App\Helpers\SiteHelper::createSlug($job->title) }}" target="_blank">
                {{ $job->uid }}
            </a></td>
        <td>{{ $job->title }}</td>
        <td>{{ $job->salary }} {{ getSystemConfig('currency').' '. number_format($job->min_salary) }} - {{ getSystemConfig('currency').' '. number_format($job->max_salary) }}</td>
        <td><a href="{{$job->website}}" target="_blank">{{ $job->name }}</a></td>
        <td>{{ $job->updated_at->diffForHumans() }}</td>
        <td>
            {{ $job->status }}
        </td>
        <td>
            @can('update_job')
            <a href="{{route('admin.auth.company.jobs.edit',$job->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
            @endcan
            @can('delete_job')
             <div class="btn-group btn-group-sm" role="group">
                <button id="userActions" type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More </button> 
                    <div class="dropdown-menu" aria-labelledby="userActions">
                    @can('delete_job')
                        @if($job->status == __('status.job.published'))
                            <a href="{{route('admin.auth.company.jobs.duplicate',$job->id)}}" style="margin-right: 5px" name="duplicate"  class="dropdown-item btn-xs btn-xs" data-toggle="tooltip" data-placement="top">Duplicate Job</a>

                            <a href="javascript:void(0)" name="confirm_item" jid="{{ $job->id }}" class="dropdown-item btn-xs updateStatus btn-xs" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
                        @else
                            <a href="javascript:void(0)" name="confirm_item" class="dropdown-item btn-xs updateStatus btn-xs" data-toggle="tooltip" jid="{{ $job->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
                            @lang('buttons.backend.access.jobs.activate')</a>
                            </a>
                        @endif

                    @endcan
                </div>
            </div>
            @endcan
        </td>
    </tr>
@endforeach
<script>

$('.updateStatus').on('click', function(){
    let job_id = $(this).attr('jid');
    const self = $(this);
    axios.post(`{{route("admin.auth.company.jobs.deactivate")}}`, {'job_id': job_id})
     .then((resp) => {
        if(resp.data == 'error') SwalMessage('Job status cannot be updated. Try-again!', 'error');
        else{
            if(resp.data.opt == 1){
                self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
                SwalMessage('Job Activated!','success');
            }else{
                self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
                SwalMessage('Job Deactivated!','success');
            }
        }
        // ld.stop();
     })
     .catch((err) => {
         SwalMessage('Job status cannot be updated. Try-again!', 'error')
         setTimeout(function() {
            }, 1500);
        console.log(err);
     })
    
});
</script>
