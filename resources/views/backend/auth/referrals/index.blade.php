@extends('backend.layouts.app')

@section('title', app_name() . ' | Referral Management')

@push('after-styles')

@endpush
<style type="text/css">
    @media (max-width: 462px){
      .input-group.filter,a.add-job {
            width: 100% !important;
            margin-bottom: -14px
        }
        button.btn.btn-primary.btn-icon-text {
            width: 100%;
        }
    }
</style>
@section('content')
<div class="row">

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
         <div class="row">
         <!--col-->
            <div class="col-sm-12 my-2" >
                <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                   <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        <div class="input-group filter">
                              <div class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                              </div>
                            <input type="text" class="form-control" name="keyword" id="search" placeholder="Search here..."  value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
                        </div>
                        &nbsp;
                        @can('add_referral')
                       <a href="{{ route('admin.auth.referrals.create') }}" class="add-job"> 
                        <button type="button" class="btn btn-primary btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="plus"></i>
                                Create New
                            </button>
                        </a>
                        @endcan
                    </div><!--btn-toolbar--> 
                </div>
            </div>
            <div class="col-sm-7">
            </div><!--col-->
        </div><!--row-->
        <div class="table-responsive">
          <table id="dataTableExample1" class="table">
            <thead>
              <tr>
                <th data-name="id" class="sortable id">#</th>
                <th data-name="name" class="sortable name">Name</th>
                <th data-name="name" class="sortable name">User Type</th>
                <th data-name="size_id" class="sortable size">Limit per user</th>
                <th data-name="location_name" class="sortable location">Start Date</th>
                <th data-name="industry_domain_name" class="sortable domain">End Date</th>
                <th data-name="amount" class="sortable tt-case">Amount</th>
                <th data-name="updated_at" class="sortable last-update">@lang('labels.backend.access.users.table.last_updated')</th>
                <th data-name="created_at" class="sortable">@lang('labels.backend.access.users.table.created_on')</th>
                <th>@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody id="table-data" >
                        
            </tbody>
         
          </table>
        </div>
       
        <div class="row" id="table-pagination">
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
{{-- @push('after-scripts') --}}
<script>
$(document).ready(function() {
   
    $('#search').keyup(function(e) {
        // if (e.keyCode == 13) {
            var q = $(this).val();
            getReferrals('&q='+q);
        // }
    });
//custom pagination...................
    getReferrals();
    function getReferrals(params='',$url='') {
        $('body').find('.table').addClass('loading1');
        var url = $url != '' ? $url : "{{ route('admin.auth.referral.list') }}"+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.table').removeClass('loading1');
                if (data.status == true) {
                    $('body').find('#table-data').html(data.table);
                    $('body').find('#table-pagination').html(data.pagination);
                }
            },
        });
    }

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getReferrals('',url);
    });

    var $sortable = $('body').find('.sortable');
    $('body').on('click', '.sortable', function() {
      
        var $this = $(this);
        var asc = $this.hasClass('asc');
        var desc = $this.hasClass('desc');
        var sort_column = $this.attr('data-name');
        $this.removeClass('asc').removeClass('desc');
        if (desc || (!asc && !desc)) {
            getReferrals('&column='+sort_column+'&sort=asc');
            $this.addClass('asc');
        } else {
            getReferrals('&column='+sort_column+'&sort=desc');
            $this.addClass('desc');
        }
          
    });
});
</script>
{{-- @endpush --}}
