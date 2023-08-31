@extends('backend.layouts.app')

@section('title', app_name() . ' | System Config Management')

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
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('System Config Management') }} 
                </h4>
            </div><!--col-->
            <div class="col-sm-7">
                <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
    
                    <div class="input-group">
                              <div class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                              </div>
                            <input type="text" class="form-control" name="keyword" id="search" placeholder="Search here..."  value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
                        </div>
                    
                </div>
            </div>
        </div><!--row-->

        <div class="row mt-4">
            <div class="table-responsive">
                <table class="table" id="dataTableExample1">
                    <thead>
                    <tr>
                        <th data-name="id" class="sortable">#</th>
                        <th style="width: 25%;" data-name="key" class="sortable">Key</th>
                        <th data-name="value" class="sortable">Value</th>
                        <th  data-name="updated_at" class="sortable no-sort" style="width: 15%;">Last Updated</th>
                        <th>@lang('labels.general.actions')</th>
                    </tr>
                    </thead>
                    <tbody id="table-data" >
                                
                    </tbody>
                </table>
            </div><!--col-->
        </div><!--row-->
        <div class="row" id="table-pagination">
        </div>
    </div><!--card-body-->
</div><!--card-->
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

{{-- @push('after-scripts') --}}
<script>
$(document).ready(function() {

  
    var $sortable = $('body').find('.sortable');
    $('body').on('click', '.sortable', function() {
      
        var $this = $(this);
        var asc = $this.hasClass('asc');
        var desc = $this.hasClass('desc');
        var sort_column = $this.attr('data-name');
        $this.removeClass('asc').removeClass('desc');
        if (desc || (!asc && !desc)) {
            systemConfigList('&column='+sort_column+'&sort=asc');
            $this.addClass('asc');
        } else {
            systemConfigList('&column='+sort_column+'&sort=desc');
            $this.addClass('desc');
        }
    });
//custom pagination..............
    systemConfigList();
    function systemConfigList(params='',$url='') {
        $('body').find('.table').addClass('loading');
        var url = $url != '' ? $url : "{{ route('admin.system-config.list',['type'=>'system-config']) }}"+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.table').removeClass('loading');
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
        systemConfigList('',url);
    });

    $('#search').keyup(function(e) {
        // if (e.keyCode == 13) {
            var q = $(this).val();
            systemConfigList('&q='+q);
        // }
    });
   
});
</script>
{{-- @endpush --}}