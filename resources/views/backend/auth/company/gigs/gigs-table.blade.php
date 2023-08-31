@foreach ($companyGigs as $key => $companyGig)
  <tr >
    <td>
      <a href="{{ env('FRONTEND_URL').'/gig/details/'.$companyGig->id.'/'.App\Helpers\SiteHelper::createSlug($companyGig->title) }}" target="_blank">
          {{ $companyGig->uid }}
      </a>
    </td>
    <td>{{$companyGig->title}}</td>
    <td>{{ getSystemConfig('currency').' '. number_format($companyGig->min_budget) }} - {{ getSystemConfig('currency').' '. number_format($companyGig->max_budget) }}</td>
    <td>{{$companyGig ? $companyGig->company_name : ''}}</td>
    <td>{{date("d-m-Y", strtotime($companyGig->updated_at))}}</td>
    <td>{{@$companyGig->status}}</td>
    <td>
      <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
         
        </div>
         @can('update_job')
            <a href="{{route('admin.auth.gigs.create',$companyGig->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
            @endcan
            @can('delete_job')
             <div class="btn-group btn-group-sm" role="group">
                <button id="userActions" type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More </button> 
                    <div class="dropdown-menu" aria-labelledby="userActions">
                        @can('update_gig')
                          <a href="{{route('admin.auth.gigs.re-new-gig',$companyGig->id)}}" style="margin-right: 5px" name="renew"  class="dropdown-item btn-xs" data-toggle="tooltip" data-placement="top">Renew Gig</a>
                        @endcan
                       @if($companyGig->status == __('status.job.published'))
                        @can('create_gig')
                          <a href="{{route('admin.auth.company.gigs.duplicate',$companyGig->id)}}" style="margin-right: 5px" name="duplicate"  class="dropdown-item btn-xs" data-toggle="tooltip" data-placement="top">Duplicate Gig</a>
                        @endcan

                        @can('update_gig')

                          <a href="javascript:void(0)" name="confirm_item" jid="{{ $companyGig->id }}" class="dropdown-item updateStatus btn-xs" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
                        @endcan
                      @else
                        @can('update_gig')
                          <a href="javascript:void(0)" name="confirm_item" class="dropdown-item updateStatus btn-xs" data-toggle="tooltip" jid="{{ $companyGig->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
                          @lang('buttons.backend.access.jobs.activate')</a>
                          </a>
                        @endcan
                      @endif
          
                    {{-- @can('delete_job')
                        @if($job->status == __('status.job.published'))
                            <a href="{{route('admin.auth.company.jobs.duplicate',$job->id)}}" style="margin-right: 5px" name="duplicate"  class="dropdown-item btn-xs btn-xs" data-toggle="tooltip" data-placement="top">Duplicate Job</a>

                            <a href="javascript:void(0)" name="confirm_item" jid="{{ $job->id }}" class="dropdown-item btn-xs updateStatus btn-xs" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
                        @else
                            <a href="javascript:void(0)" name="confirm_item" class="dropdown-item btn-xs updateStatus btn-xs" data-toggle="tooltip" jid="{{ $job->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
                            @lang('buttons.backend.access.jobs.activate')</a>
                            </a>
                        @endif

                    @endcan --}}
                </div>
            </div>
            @endcan
    </td>
  </tr>
@endforeach
<script>
  $('.updateStatus').on('click', function(){
      let gig_id = $(this).attr('jid');
      const self = $(this);
      axios.post(`{{route("admin.auth.gigs.deactivate")}}`, {'gig_id': gig_id})
       .then((resp) => {
          if(resp.data == 'error') SwalMessage('Gig status cannot be updated. Try-again!', 'error');
          else{
              if(resp.data.opt == 1){
                  self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
                  SwalMessage('Gig Activated!','success');
              }else{
                  self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
                  SwalMessage('Gig Deactivated!','success');
              }
          }
          // ld.stop();
       })
       .catch((err) => {
           SwalMessage('Gig status cannot be updated. Try-again!', 'error')
           setTimeout(function() {
              }, 1500);
          console.log(err);
       })
      
  });
</script>