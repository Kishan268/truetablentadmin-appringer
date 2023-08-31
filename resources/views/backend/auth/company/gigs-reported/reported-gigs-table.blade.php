@foreach ($companyGigs as $key => $companyGig)
  <tr >
    <td>
    
      <a href="{{ env('FRONTEND_URL').'/gigs/details/'.$companyGig->id.'/'.App\Helpers\SiteHelper::createSlug($companyGig->title) }}" target="_blank">
          {{ $companyGig->uid }} TG-0000{{$key+1}} - {{ $companyGig->title }}
      </a>
    </td>
    <td>{{$companyGig->title}}</td>
    <td>{{ getSystemConfig('currency').' '. number_format($companyGig->min_budget) }} - {{ getSystemConfig('currency').' '. number_format($companyGig->max_budget) }}</td>
    <td>{{$companyGig ? $companyGig->company_name : ''}}</td>
    <td>{{date("d-m-Y", strtotime($companyGig->created_at))}}</td>
    <td>{{@$companyGig->status}}</td>
    <td>
      <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
          @if($companyGig->status == __('status.job.published'))
              <a href="javascript:void(0)" name="confirm_item" jid="{{ $companyGig->id }}" class="btn btn-outline-danger updateStatus btn-xs" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
          @else
              <a href="javascript:void(0)" name="confirm_item" class="btn btn-outline-primary updateStatus btn-xs" data-toggle="tooltip" jid="{{ $companyGig->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
              @lang('buttons.backend.access.jobs.activate')</a>
              </a>
          @endif
        </div>
    </td>
  </tr>
@endforeach
<script>
  $('.updateStatus').on('click', function(){
      let gig_id = $(this).attr('jid');
      const self = $(this);
      axios.post(`{{route("admin.auth.gigs.deactivate")}}`, {'gig_id': gig_id})
       .then((resp) => {
          if(resp.data == 'error') SwalMessage('Reported Gig status cannot be updated. Try-again!', 'error');
          else{
              if(resp.data.opt == 1){
                  self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
                  SwalMessage('Reported Gig Activated!','success');
              }else{
                  self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
                  SwalMessage('Reported Gig Deactivated!','success');
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