 @foreach($system_configs  as $key => $system_config)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $system_config->key }}</td>
        <td>{{ $system_config->value }}</td>
        <td>{{ $system_config->updated_at != null ? $system_config->updated_at->diffForHumans() : '' }}</td>
        <td>
             <a href="{{route('admin.system-config.edit',$system_config->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
        </td>
    </tr>
@endforeach
<script>
   

$('.updateStatus').on('click', function(){
    // let job_id = $(this).attr('jid');
    // const self = $(this);
    // axios.post(`{{route("admin.auth.company.jobs.deactivate")}}`, {'job_id': job_id})
    //  .then((resp) => {
    //     if(resp.data == 'error') SwalMessage('Job status cannot be updated. Try-again!', 'error');
    //     else{
    //         if(resp.data.opt == 1){
    //             self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
    //             SwalMessage('Job Activated!','success');
    //         }else{
    //             self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
    //             SwalMessage('Job Deactivated!','success');
    //         }
    //     }
    //     // ld.stop();
    //  })
    //  .catch((err) => {
    //      SwalMessage('Job status cannot be updated. Try-again!', 'error')
    //      setTimeout(function() {
    //         }, 1500);
    //     console.log(err);
    //  })
    
});
</script>
