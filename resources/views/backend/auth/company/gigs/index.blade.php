@extends('backend.layouts.app')

@section('title', app_name() . ' | Gigs Management')

@push('after-styles')
    <style>
        td {
            text-transform: capitalize;
        }
        
    </style>
<link href="{{ asset('css/custom-filters.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                  <div class="col-md-12 my-2">
                    <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        <div class="btn-toolbar float-right1" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                            <div class="row filter-fix">
                                <div class="col-md-5 mt-1">
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
                                @can('add_gig')
                                    <div class="col-md-3 mt-1">
                                        <a href="{{route('admin.auth.gigs.create')}}">
                                            <button type="button" class="btn btn-primary btn-icon-text">
                                                <i class="btn-icon-prepend"></i>
                                                Create New
                                            </button>
                                        </a>
                                    </div>&nbsp;
                                @endcan
                                <div class="col-md-4 mt-1 panel-heading">
                                    <a class="btn btn-primary advance-filter panel-title" data-bs-toggle="collapse"
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
                                    id="profileDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ isset($status) && $status != '' ? $status : 'All' }} Gigs
                                </a>&nbsp;
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item status-btn" data-status="All"
                                        href="javascript::void()">All Gigs</a>
                                    <a class="dropdown-item status-btn" data-status="Activated"
                                        href="javascript::void()">Activated Gigs</a>
                                    <a class="dropdown-item status-btn" data-status="Deactivated"
                                        href="javascript::void()">Deactivated Gigs</a>
                                </div>
                            </div>
                           
                            <div class="row filter-fix">
                                <div class="col-md-5 mt-1">
                                    <input type="text" max="{{ date('Y-m-d') }}" name="from_date"
                                        placeholder="From Date" class="form-control" id="from_date"
                                        autocomplete="off">
                                </div>
                                <div class="col-md-5 mt-1">
                                    <input type="text" max="{{ date('Y-m-d') }}" name="to_date"
                                        placeholder="To Date" class="form-control" id="to_date"
                                        autocomplete="off">
                                </div>
                                <div class="col-md-1 mt-1">
                                    <button class="btn btn-primary " type="submit" tooltip="Export" id="export"
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
                <div class="table-responsive">
                    <table class="table" id="dataTableExample1">
                        <thead>
                            <tr>
                                <th data-name="id" class="sortable">#</th>
                                <th style="width: 25%;" data-name="title" class="sortable">Title</th>
                                <th data-name="min_budget" class="sortable">Budget</th>
                                <th data-name="name" class="sortable">Company Name</th>
                                <th data-name="updated_at" class="sortable no-sort" style="width: 15%;">Last Updated</th>
                                <th style="width: 7%;" data-name="status" class="sortable">Status</th>
                                <th>@lang('labels.general.actions')</th>
                            </tr>
                        </thead>
                        <tbody id="table-data">

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
    <input type="hidden" name="status" value="{{ isset($status) && $status != '' ? $status : 'All' }}" id="status">

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
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
                getGigs('&column=' + sort_column + '&sort=asc');
                $this.addClass('asc');
            } else {
                getGigs('&column=' + sort_column + '&sort=desc');
                $this.addClass('desc');
            }
        });
        //custom pagination..............
        getGigs();

        function getGigs(params = '', $url = '') {
            $('body').find('.table').addClass('loading1');
            var searchStatus = $('#status').val();
            params += "&status=" + searchStatus;
            var url = $url != '' ? $url :
                "{{ route('admin.auth.gigs.gigs-particular-section-list', ['type' => 'gigs']) }}" + params;
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
            getGigs('', url);
        });

        $('#search').keyup(function(e) {
            var q = $(this).val();
            getGigs('&q=' + q);
        });
        $(".status-btn").click(function() {
            let status = $(this).attr('data-status');
            $('#status').val(status)
            $('#search').val('')
            $(".status-text").text(status + ' Gigs');
            getGigs('&status=' + status);
        });

        //filters......................
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
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            getGigs('', url);
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
                getGigs('&column=' + sort_column + '&sort=asc' + '&from_date=' + from_date +
                    '&to_date=' + to_date);
                $this.addClass('asc');
            } else {
                getGigs('&column=' + sort_column + '&sort=desc' + '&from_date=' + from_date +
                    '&to_date=' + to_date);
                $this.addClass('desc');
            }

        });

        function dateFilter() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date === '' && to_date) {
                SwalMessage('Please select From date first!', 'warning');
                $('#to_date').val('');
                getGigs();
                return false;
            }
            if (from_date && to_date) {
                if (from_date <= to_date) {
                    getGigs('&from_date=' + from_date + '&to_date=' + to_date, '');
                } else {
                    $('#to_date').val('');
                    SwalMessage('To date must be greater than From date!', 'warning');
                    getGigs();
                }
                getGigs('&from_date=' + from_date + '&to_date=' + to_date, '');
            } else {
                getGigs();
            }
        }

        $('#export').on('click', function() {
            $('body').find('.table').addClass('loading1');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date && to_date) {
                var params = '&from_date=' + from_date + '&to_date=' + to_date;
                var url = "{{ route('admin.auth.gigs.exports', ['type' => 'export-gigs']) }}" +
                    params;
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
                        link.download = 'gigs-' + from_date + '-to-' + to_date +
                            `.xlsx`;
                        link.click();

                    },
                });
            } else if (from_date === '' && to_date === '') {
                SwalMessage('Please select From date and To date!', 'warning');
                $('body').find('.table').removeClass('loading1');
                return false;
            } else {
                SwalMessage('Please select To date!', 'warning');
                $('body').find('.table').removeClass('loading1');
                return false;
            }
        });

    });
</script>
{{-- @endpush --}}
