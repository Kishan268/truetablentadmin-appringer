{{ html()->modelForm($logged_in_user, 'POST', route('frontend.user.preferences.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
    @method('PATCH')

    @php $details = $logged_in_user->details; @endphp
    <h4 class="mt-3">WorkProfile Preferences</h4>
    <hr/>
    <div class="row">
        <div class="col">
            <h6 class="text-info animated shake addCompanyText d-none"><i class="fas fa-question-circle"></i> Looks like this company is not yet available on {{ env('APP_NAME') }}! Click <a href="javascript:void(0);" data-toggle="modal" data-target="#companyAddModal"><b>HERE</b></a> to add it? <small>(Doing this will still block your profile as and when it is available)</small></h6>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="">Restrict the following companies from viewing my WorkProfile:</label>
                <select class="form-control ml-5" name="company" id='searchCompany'></select>
                <button class="btn btn-info btn-sm ml-5 blockCompany" type="button"><i class="fas fa-plus"></i> Add</button>
                {{-- <button class="btn btn-info btn-sm ml-5 blockCompany" type="button"><i class="fas fa-plus"></i> Add</button> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{-- @if($logged_in_user->blocked_companies_list->count()) --}}
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id='blockedCompaniestBody'>
                    @php $i=0; @endphp
                    @if(!$logged_in_user->blocked_companies_list->count())
                    <tr class="text-center empty"><td colspan="3">No companies have been blocked.</td></tr>
                    @endif
                    @foreach ($logged_in_user->blocked_companies_list as $comp)
                        <tr cid='{{ $comp["id"] }}'>
                            <td>{{ ++$i }}</td>
                            <td>{{ $comp['name'] }}</td>
                            <td><button type="button" class="btn btn-warning btn-xs unBlockCompany" cid='{{ $comp["id"] }}'><i class="fas fa-times"></i> Remove</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- @else --}}
                {{-- <h3 class="text-center mt-5"><i class="fas fa-info-circle"></i> No companies have been blocked yet.</h3> --}}
            {{-- @endif --}}
        </div>
    </div>

    <div class="modal fade" id="companyAddModal" tabindex="-1" role="dialog" aria-labelledby="companyAddModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companyAddModalLabel">Add Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="companyName">Company Name</label>
                            <input type="text" class="form-control" id="companyName" aria-describedby="companyHelp"
                                placeholder="Enter Complete Company Name">
                            <small id="companyHelp" class="form-text text-muted">Ex. Microsoft Corporation</small>
                            <span class="text-danger companyNameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="companyWebsite">Company Website</label>
                            <input type="text" class="form-control" id="companyWebsite" placeholder="Enter company Website">
                            <small id="" class="form-text text-muted">Ex. www.microsoft.com</small>
                            <span class="text-danger companyWebsiteError"></span>
                        </div>
                        <h6 class="d-none companyMatchNameAlready text-success text-center animated flashIn"><i class="fas fa-info-circle"></i>Your entry matches with "<span class='companyMatchName font-weight-bold'></span>". Do you want to block <span class='companyMatchName font-weight-bold'></span>?</h6>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success addCompany">Add & Block</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@push('after-scripts')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            let cname;

            function addCompany(){
                Swal.fire({
                    title: 'Looks like this company is not yet available on {{ env('APP_NAME') }}!',
                    html: "Would you like to add it?<br/><small>Doing this will still block your profile as and when it is available</small>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        
                    }
                })
            }
            
            $('select#searchCompany').select2({
                placeholder: 'Start typing a company name...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: true,
                scrollAfterSelect: true,
                tags: false,
                width: '40%',
                ajax: {
                    url: "/getCompanies/true",
                    type: "post",
                    dataType: 'json',
                    processResults: function (data) {
                        console.log(data.results.length);
                        $('.addCompanyText').addClass('d-none');
                        if(data.results.length == 0){
                            $('.addCompanyText').removeClass('d-none');
                            let searchText = $('input.select2-search__field').val();
                            $('input#companyName').val(searchText);
                            // addCompany();
                        }
                        return {
                            results: data.results
                        };
                    }
                }
            }).on('select2:select', function(e){
                cname = e.params.data.text;
            });

            function blockCompany(cid, cname){
                $.post("/blockCompany/"+cid).done(function(e){
                    if(e == 'success'){
                        let count = 0;
                        if($('tr.empty').length){
                            count = 1;
                        }else{
                            count = $('#blockedCompaniestBody').children('tr').length + 1;
                        }
                        let row = `<tr cid='${cid}'>
                            <td>${count}</td>
                            <td>${cname}</td>
                            <td><button type="button" class="btn btn-warning btn-xs unBlockCompany" cid='${cid}'><i
                                        class="fas fa-times"></i> Remove</button></td>
                        </tr>`;
                        if($('tr.empty').length){
                            $('#blockedCompaniestBody').html(row);
                        }else{
                            $('#blockedCompaniestBody').append(row);
                        }
                        toastr.success('Company blocked Successfully!');
                        $('select#searchCompany').val(null).trigger('change');
                    }else{
                        toastr.error('Company cannot be blocked at this moment, please try-again or contact support!!', 'System Error!');
                    }
                });
            }

            $('.blockCompany').on('click', function(){
                let cid = $('select#searchCompany').val();
                if(!cid){
                    toastr.warning('Please select a company to block!');
                    return false;
                }
                blockCompany(cid, cname);
            });
            
            $('#blockedCompaniestBody').on('click', '.unBlockCompany', function(){
                let cid = $(this).attr('cid');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will make your profile accessible by this company!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.post("/unBlockCompany/"+cid).done(function(e){
                            if(e == 'success'){
                                $(`tr[cid=${cid}]`).remove();
                                toastr.success('Company unblocked Successfully!');
                                if($('#blockedCompaniestBody').children('tr').length == 0){
                                    $('#blockedCompaniestBody').html('<tr class="text-center empty"><td colspan="3">No companies have been blocked.</td></tr>');
                                }else{
                                }
                            }else{
                                toastr.error('Company cannot be unblocked at this moment, please try-again or contact support!!', 'System Error!');
                            }
                        });
                    }
                })
            });

            $('.addCompany').on('click', function(){
                let add = true;
                let attr = $(this).attr('cid');

                if(typeof attr !== typeof undefined && attr !== false){
                    // let cid = $(this).attr('cid');
                    // console.log(attr);
                    blockCompany(attr, cname);
                    $('#companyAddModal').modal('hide');
                    add = false;
                }
                if(!add){
                    return false;
                }
                let companyName = $('#companyName').val();
                let companyWebsite = $('#companyWebsite').val();
                $.post("{{ route('frontend.candidate.addNblockCompany') }}", {'company_name': companyName, 'company_website': companyWebsite})
                 .done(function(e){
                    if(e.errors){
                        if(e.errors.company_name){
                            $('.companyNameError').text(e.errors.company_name);
                        }
                        if(e.errors.company_website){
                            $('.companyWebsiteError').text(e.errors.company_website);
                        }
                        if(e.errors.company_exists){
                            $('.companyMatchName').text(e.errors.company_exists.name);
                            $('.companyMatchNameAlready').removeClass('d-none');
                            $('button.addCompany').text('Block').attr('cid', e.errors.company_exists.id);
                            cname = e.errors.company_exists.name;
                        }
                    }else if(e.success){
                        blockCompany(e.success.id, e.success.name);
                        $('#companyAddModal').modal('hide');
                    }else{
                        toastr.error('Company cannot be added/blocked at this moment, please try-again or contact support!!', 'System Error!');
                    }
                 });
            });

            $('#companyAddModal').on('hidden.bs.modal', function (e) {
                $('.addCompanyText').addClass('d-none');
                $('button.addCompany').text('Add & Block').removeAttr('cid');
                $('.companyMatchNameAlready').addClass('d-none');
                $('.companyMatchName').text('');
                $('#companyName').val('');
                $('#companyWebsite').val('');
            });
        });
    </script>
@endpush
