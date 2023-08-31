 
@foreach($jobs as $job)
    <tr>
        <td>
            <a href="{{ env('FRONTEND_URL').'/job/details/'.$job->id.'/'.App\Helpers\SiteHelper::createSlug($job->title) }}" target="_blank">
                {{ $job->job->uid }} - {{ $job->job->title }}
            </a>
        </td>
        <td >{{ $job->user == null ? '' : $job->user->uid }} - {{ $job->user == null ? 'NA' : $job->user->full_name }} </td>
        <td >{{ $job->issue_id == null ? $job->flag_type: $job->issue->name }}</td>
        <td>{{ $job->flag_msg }}</td>
        {{-- <td>{{ $user->roles_label }}</td> --}}
        {{-- <td>{{ $user->permissions_label }}</td> --}}
        {{-- <td>@include('backend.auth.user.includes.social-buttons', ['user' => $user])</td> --}}
        <td>{{ $job->updated_at->diffForHumans() }}</td>
        <td>
            {{ $job->job->status }}
        </td>
        <td>
            <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
                @if($job->job->status == __('status.job.published'))
                    <a href="javascript:void(0)" name="confirm_item" jid="{{ $job->job->id }}" class="btn btn-outline-danger updateStatus btn-xs" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
                @else
                    <a href="javascript:void(0)" name="confirm_item" class="btn btn-outline-primary updateStatus btn-xs" data-toggle="tooltip" jid="{{ $job->job->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
                    @lang('buttons.backend.access.jobs.activate')</a>
                    </a>
                @endif
            </div>

        </td>
        {{-- <td class="btn-td">@include('backend.auth.user.includes.actions', ['user' => $user])</td> --}}
    </tr>
@endforeach
<script type="text/javascript">
     $(function(){
        $('.updateStatus').on('click', function(){
            let job_id = $(this).attr('jid');
            const self = $(this);
            axios.post(`{{route("admin.auth.company.jobs.deactivate")}}`, {'job_id': job_id})
                 .then((resp) => {
                    console.log(resp)
                    if(resp.data == 'error') SwalMessage('Job status cannot be updated. Try-again!','error');
                    else{
                        if(resp.data.opt == 1){
                            self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
                            SwalMessage('Job Activated!','success');
                            
                        }else{
                            self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
                            SwalMessage('Job Activated!','success');
                        }
                    }
                 })
                 .catch((err) => {
                    SwalMessage('Job status cannot be updated. Try-again!','error');
                    console.log(err);
                 })
            
            // $(this).html('<i class="fas fa-eye-slash"></i>');
        });
    });

</script>