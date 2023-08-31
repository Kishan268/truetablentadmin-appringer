@extends('backend.layouts.app')

@section('title', app_name() . ' | Notification Settings')

@push('after-styles')

@endpush
<link href="{{ asset('css/custom-filters.css') }}" rel="stylesheet" />

<style type="text/css">
    @media screen and (min-width: 768px) {
       .search-filter {
            flex: 0 0 auto;
            width: 100% !important;
        }
    }
</style>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!--col-->
                <div class="col-md-12 my-2">
                    <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        <div class="btn-toolbar float-right1" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                            <div class="row filter-fix">
                                <div class="col-md-5 mt-1 search-filter">
                                    <div class="form-group has-search">
                                        <div><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                                viewBox="-10 0 50 20" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-search form-control-feedback">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65">
                                                </line>
                                            </svg></div>
                                        <input type="search" class="form-control" name="keyword" id="search"
                                            placeholder="Search here..."
                                            value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
                                    </div>
                                </div>&nbsp;
                            </div>

                        </div>
                        <!--btn-toolbar-->
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="table-responsive">
                    <table class="table" id="dataTableExample1">
                        <thead>
                            <tr>
                                <th data-name="id" class="sortable">#</th>
                                <th data-name="name" class="sortable">Name</th>
                                <th data-name="subject" class="sortable">Subject</th>
                                <th data-name="is_mail_enabled" class="sortable">Email Enabled</th>
                                <th data-name="is_sms_enabled" class="sortable">SMS Enabled</th>
                                <th data-name="is_wa_enabled" class="sortable">WhatsApp Enabled</th>
                                <th>@lang('labels.general.actions')</th>
                            </tr>
                        </thead>
                        <tbody id="test" class="test">

                        </tbody>
                    </table>
                </div>
                <!--col-->
            </div>
            <!--row-->
            <div class="row" id="table-pagination">
            </div>
        </div>
        <!--card-body-->
    </div>
    <!--card-->
    {{-- <input type="hidden" name="status" value="{{ isset($status) && $status != '' ? $status : 'All' }}" id="status"> --}}

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

{{-- @push('after-scripts') --}}
<script>
     $(document).ready(function(){

   //custom pagination...................
        getNotificationList();
        function getNotificationList(params = '', $url = '') {
            var searchStatus = $('#status').val();
            params += "&status=" + searchStatus;
            var url = $url != '' ? $url : "{{ route('admin.auth.notification.list', ['type' => 'notification-list']) }}" +
                params;
            $.ajax({
                type: "get",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == true) {
                        $('.test').html(data.table);
                        $('body').find('#table-pagination').html(data.pagination);
                    }
                },
            });
        }
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            getNotificationList('', url);
        });
        var $sortable = $('body').find('.sortable');
        $('body').on('click', '.sortable', function() {

            var $this = $(this);
            var asc = $this.hasClass('asc');
            var desc = $this.hasClass('desc');
            var sort_column = $this.attr('data-name');
            $this.removeClass('asc').removeClass('desc');
            if (desc || (!asc && !desc)) {
                getNotificationList('&column=' + sort_column + '&sort=asc');
                $this.addClass('asc');
            } else {
                getNotificationList('&column=' + sort_column + '&sort=desc');
                $this.addClass('desc');
            }

        });
        $('#search').keyup(function(e) {
            var q = $(this).val();
            getNotificationList('&q=' + q );
        });
    });

</script>
{{-- @endpush --}}
