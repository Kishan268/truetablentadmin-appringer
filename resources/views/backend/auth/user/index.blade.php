@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links', ['type' => $type]) --}}

@endsection
<link href="{{ asset('css/custom-filters.css') }}" rel="stylesheet" />

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="row">
                <!--col-->
                <div class="col-md-12 my-2">
                    <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                            <div class="row testtt">
                                <div class="col-md-5 mt-1">
                                    <!-- Actual search box -->
                                    <div class="form-group has-search">
                                        <div><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                                viewBox="-10 0 50 20" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-search form-control-feedback">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg></div>
                                        <input type="search" class="form-control" name="keyword" id="search"
                                            placeholder="Search here..."
                                            value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
                                    </div>
                                </div>
                                @can('add_user')
                                    <div class="col-md-3 mt-1">
                                        <a href="{{ route('admin.auth.user.create', ['type' => isset($type) ? $type : '']) }}">
                                            <button type="button" class="btn btn-primary btn-icon-text">
                                                <i class="btn-icon-prepend"></i>
                                                Create New
                                            </button>
                                        </a>
                                    </div>
                                @endcan
                                <div class="col-md-4 mt-1">
                                    <a class="btn btn-primary advance-filter panel-title " data-bs-toggle="collapse"
                                        href="#collapseExample" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        Advanced Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--btn-toolbar-->
                    </div>
                </div>
            </div>
            <!--row-->
            <div class="row">
                <div class="col-md-12 my-2 collapse" id="collapseExample">
                    <div class="btn-toolbar custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        <div class="btn-toolbar " role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                            <div class="dropdown mt-1">
                                <a class="btn btn-outline-primary btn-sm dropdown-toggle status-text" href="#"
                                    id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ isset($status) && $status != '' ? $status : 'All' }} Users
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item status-btn" data-status="All" href="javascript::void()">All
                                        Users</a>
                                    <a class="dropdown-item status-btn" data-status="Activated"
                                        href="javascript::void()">Activated Users</a>
                                    <a class="dropdown-item status-btn" data-status="Deactivated"
                                        href="javascript::void()">Deactivated Users</a>
                                </div>
                            </div> &nbsp; &nbsp;
                            <div class="row filter-fix">
                                <div class="col-md-4 mt-1">
                                    <input type="text" max="{{ date('Y-m-d') }}" name="from_date" placeholder="From Date"
                                        class="form-control" id="from_date" autocomplete="off">
                                </div>
                                <div class="col-md-4 mt-1">
                                    <input type="text" max="{{ date('Y-m-d') }}" name="to_date" placeholder="To Date"
                                        class="form-control" id="to_date" autocomplete="off">
                                </div>
                                <div class="col-md-2 mt-1">
                                    <button class="btn btn-outline-primary " type="submit" tooltip="Export" id="export"
                                        style="height: 37px;     margin-top: 1px;"><i
                                            class="fa fa-file-excel-o"></i></button>
                                </div>
                            </div>
                        </div>
                        <!--btn-toolbar-->
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th data-name="first_name" class="sortable">@lang('labels.backend.access.users.table.name')</th>
                                    <th data-name="email" class="sortable">@lang('labels.backend.access.users.table.email')</th>
                                    <th data-name="email_verified_at" class="sortable">@lang('labels.backend.access.users.table.confirmed')</th>
                                    <th data-name="updated_at" class="sortable">@lang('labels.backend.access.users.table.last_updated')</th>
                                    <th data-name="updated_at" class="sortable">@lang('labels.backend.access.users.table.created_on')</th>
                                    <th>@lang('labels.general.actions')</th>
                                </tr>
                            </thead>
                            <tbody id="table-data">

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--col-->
            </div>
            <!--row-->
            <div class="row" id="table-pagination">
            </div>
            <!--row-->
        </div>
        <!--card-body-->
    </div>
    <!--card-->
    <input type="hidden" name="status" value="{{ isset($status) && $status != '' ? $status : 'Activated' }}"
        id="status">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

{{-- @push('after-scripts') --}}
<script>
    $(document).ready(function() {
        getUsers();

        function dateFilter() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var searchStatus = $('#status').val();
            if (from_date == '' && to_date) {
                SwalMessage('Please select From date first!', 'warning');
                $('#to_date').val('');
                getUsers();
                return false;
            }
            if (from_date && to_date) {
                if (from_date <= to_date) {
                    getUsers('&from_date=' + from_date + '&to_date=' + to_date, '');
                } else {
                    $('#to_date').val('');
                    SwalMessage('To date must be greater than From date!', 'warning');
                    getUsers();
                }
                getUsers('&from_date=' + from_date + '&to_date=' + to_date, '');
            } else {
                let url = "{{ route('admin.auth.user.list', ['type' => $type]) }}";
                getUsers();
            }
        }

        function getUsers(params = '', $url = '') {

            $('body').find('.table').addClass('loading1');
            let status = "{{ isset($status) ? $status : '' }}";
            var searchStatus = $('#status').val();
            params += "&status=" + searchStatus;
            var url = $url != '' ? $url : "{{ route('admin.auth.user.list', ['type' => $type]) }}" + params;
            $.ajax({
                type: "get",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
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
            getUsers('', url);
        });

        $('#search').keyup(function(e) {
            var q = $(this).val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            getUsers('&q=' + q + '&from_date=' + from_date + '&to_date=' + to_date);
        });
        var $sortable = $('body').find('.sortable');
        $('body').on('click', '.sortable', function() {

            var $this = $(this);
            var asc = $this.hasClass('asc');
            var desc = $this.hasClass('desc');
            var sort_column = $this.attr('data-name');
            $this.removeClass('asc').removeClass('desc');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (desc || (!asc && !desc)) {
                getUsers('&column=' + sort_column + '&sort=asc' + '&from_date=' + from_date +
                    '&to_date=' + to_date);
                $this.addClass('asc');
            } else {
                getUsers('&column=' + sort_column + '&sort=desc' + '&from_date=' + from_date +
                    '&to_date=' + to_date);
                $this.addClass('desc');
            }

        });
        $(".status-btn").click(function() {
            let status = $(this).attr('data-status');
            $('#status').val(status)
            $('#search').val('')
            $(".status-text").text(status + ' Users');
            let url = "{{ route('admin.auth.user.list', ['type' => $type]) }}" + '&status=' + status;
            getUsers('', url);
            $('#from_date').val('');
            $('#to_date').val('');
        });

        $(function() {
            $("#from_date,#to_date").datepicker({
                dateFormat: "dd-mm-yy",
                maxDate: '+0D',
                onSelect: function(selectedDate) {
                    if (this.id == 'from_date') {
                        var dateMin = $('#from_date').datepicker("getDate");
                        $('#to_date').datepicker("option", "minDate", dateMin);
                        dateFilter()
                    } else {
                        var dateMin = $('#to_date').datepicker("getDate");
                        $('#from_date').datepicker("option", "maxDate", dateMin);
                        dateFilter()
                    }
                }
            });
        });

        $('#export').on('click', function() {
            $('body').find('.table').addClass('loading1');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date && to_date) {
                let status = "{{ isset($status) ? $status : '' }}";
                var searchStatus = $('#status').val();
                var params = "&status=" + searchStatus + '&from_date=' + from_date + '&to_date=' +
                    to_date;
                var url = "{{ route('admin.auth.user.export', ['type' => $type]) }}" + params;
                $.ajax({
                    type: "post",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, textStatus, request) {
                        $('body').find('.table').removeClass('loading1');
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'users-' + from_date + '-to-' + to_date + `.xlsx`;
                        link.click();

                    },
                });
            } else if (from_date === '' && to_date === '') {
                SwalMessage('Please select From date and To date!', 'warning');
                $('body').find('.table').removeClass('loading1');
                return false;
            } else {
                SwalMessage('Please select to date!', 'warning');
                $('body').find('.table').removeClass('loading1');
                return false;
            }
        });

        function SwalMessage(message, type) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: type,
                title: message
            })
        }
    });
</script>
{{-- @endpush --}}
