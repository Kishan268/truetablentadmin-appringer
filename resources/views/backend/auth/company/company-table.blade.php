 @foreach($companies as $company)
    <tr>
        <td>{{ $company->id }}</td>
        <td><a href='{{ $company->website }}' target="_blank">{{ $company->name }}</a></td>
        <td>{{ $company->size ? $company->size->name :''}}</td>
        <td>{{ $company->location_name }}</td>
        <td>{{ $company->industry_domain_name }}</td>
        <td>
            <button type="button" class="btn btn-primary badge badge-success addCash fa fa-plus" cid='{{$company->id}}'  data-bs-toggle="modal" data-bs-target="#addCashModal" id='addCashMenu-{{$company->id}} 'title="Add">
        </button>
            &nbsp;&nbsp;
            <span id='remainingViews-{{ $company->id }}'> {{ $company->amount ? $company->amount : 0 }} </span>
        </td>
        <td>{{ \Carbon\Carbon::parse($company->updated_at)->diffForHumans() }}</td>
        <td>{{ date('d-m-Y', strtotime($company->created_at)) }}</td>
        <td>
            
            @can('update_company')
                <a href="{{route('admin.auth.company.edit',$company->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
            @endcan

            <div class="btn-group btn-group-sm" role="group">
                <button id="companyActions" type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More </button> 
                    <div class="dropdown-menu" aria-labelledby="companyActions">
                        @can('delete_company')
                            @if($company->trashed())
                                <a href="javascript:void(0)" name="confirm_item" class="dropdown-item btn-xs updateStatus" data-toggle="tooltip" jid="{{$company->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.activate')">
                                @lang('buttons.backend.access.jobs.activate')</a>
                                </a>
                                
                            @else
                                <a href="javascript:void(0)" name="confirm_item" jid="{{ $company->id }}" class="dropdown-item btn-xs updateStatus" data-toggle="tooltip" data-placement="top">@lang('buttons.backend.access.jobs.deactivate')</a>
                            @endif
                        @endcan
                       @can('delete_company')
                        @if($company->is_deleted === '0')
                            <a href="javascript:void(0)" style="margin-left: 3px" name="confirm_item" class="dropdown-item btn-xs delete" data-toggle="tooltip" jid="{{$company->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.delete')">
                            @lang('buttons.backend.access.jobs.delete')</a>
                            </a>
                        @else
                            <a href="javascript:void(0)" style="margin-left: 3px" name="confirm_item" class="dropdown-item btn-xs btn-xs restore" data-toggle="tooltip" jid="{{$company->id }}" data-placement="top" title="@lang('buttons.backend.access.jobs.restore')">
                            @lang('buttons.backend.access.jobs.restore')</a>
                            </a>
                        @endif
                    @endcan
                </div>
            </div>
        </td>
    </tr>
@endforeach
<script type="text/javascript">
    $('.updateStatus').on('click', function(){
        let company_id = $(this).attr('jid');
        const self = $(this);
        console.log(self)
        axios.post(`{{route("admin.auth.company.deactivate")}}`, {'company_id': company_id})
             .then((resp) => {
                if(resp.data == 'error') {
                    SwalMessage('Company status cannot be updated. Try-again!', 'error');
                }
                else{
                    if(resp.data.opt == 1){
                        self.attr('title', 'Deactivate').html('Deactivate').removeClass('btn-outline-primary').addClass('btn-outline-danger');
                        SwalMessage('Company Activated!', 'success');
                    }else{
                        self.attr('title', 'Activate').html('Activate').removeClass('btn-outline-danger').addClass('btn-outline-primary');
                        SwalMessage('Company Deactivated!', 'success');
                    }
                }
             })
             .catch((err) => {
                SwalMessage('Company status cannot be updated. Try-again!', 'error');
                console.log(err);
             })
        
        // $(this).html('<i class="fas fa-eye-slash"></i>');
    });


    $('.delete').on('click', function(){
        let company_id = $(this).attr('jid');
        const self = $(this);
        console.log(self)
        axios.post(`{{route("admin.auth.company.delete-data")}}`, {'company_id': company_id})
             .then((resp) => {
                let data = resp.data.data;
                let text = "Are you sure?\n\nIt'll delete the following data\n\nJobs: "+data.jobs_count+"\nGigs: "+data.gigs_count+"\nRecruiters: "+data.recruiters_count;
                if (confirm(text) == true) {
                    deleteCompany(company_id);
                }
             })
             .catch((err) => {
                SwalMessage('Something went wrong, Please try again!', 'error');
                console.log(err);
             })
        
        // $(this).html('<i class="fas fa-eye-slash"></i>');
    });

    function deleteCompany(company_id) {
        axios.post(`{{route("admin.auth.company.delete")}}`, {'company_id': company_id})
             .then((resp) => {
            SwalMessage('Company deleted successfully', 'success');
            location.reload();
         })
         .catch((err) => {
            SwalMessage('Something went wrong, Please try again!', 'error');
            console.log(err);
         })
    }


    $('.restore').on('click', function(){
        let company_id = $(this).attr('jid');
        const self = $(this);
        console.log(self)
        axios.post(`{{route("admin.auth.company.restore")}}`, {'company_id': company_id})
             .then((resp) => {
                SwalMessage('Company restored successfully', 'success');
                location.reload();
             })
             .catch((err) => {
                SwalMessage('Something went wrong, Please try again!', 'error');
                console.log(err);
             })
        
        // $(this).html('<i class="fas fa-eye-slash"></i>');
    });

    $(function(){
        let cid;
        $('.addCash').on('click', function(){
            var cid = $(this).attr('cid');
            $('.cashAmount').val(0);
            $('.invalidAmount').addClass('d-none');
            // $('#addCashModal').modal('show');
            // $('#addCashModal').modal({
            //     backdrop: 'static',
            //     keyboard: false
            // });
            $('#addCashModalBtn').attr('data-id', cid);

        });

        $('#addCashModalBtn').on('click', function(){
            var cid = $(this).attr('data-id');
            let amount = $('.cashAmount').val();
            if(amount.length == 0){
                $('.invalidAmount').removeClass('d-none');
                return false;
            }
            $(this).prop('disabled',true);
            axios.post('{{route("admin.auth.company.addCash")}}', {'amount': amount, 'cid': cid})
                 .then((resp) => {
                    if(resp.data == 'error'){

                        SwalMessage('Transaction cannot be performed. Try-again!', 'error');
                    } 
                    else{
                        SwalMessage('TT-Cash added successfully', 'success');
                        $(`span#remainingViews-${cid}`).text(resp.data.amount);
                        $('#addCashModal').modal('hide');
                    }
                    // ld.stop();
                    $(this).prop('disabled',false);
                 })
                 .catch((err) => {
                    SwalMessage('Transaction cannot be performed. Try-again!', 'error');
                    console.log(err);
                    $(this).prop('disabled',false);
                 })
        });
    });
</script>