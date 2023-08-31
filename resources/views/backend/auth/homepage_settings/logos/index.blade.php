@extends('backend.layouts.app')

@section('title', app_name() . ' | Featured Logos')

@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css'>
    <style type="text/css">
        img.mx-2.icon {
            position: absolute;
            left: 40.83%;
            right: 22.83%;
            top: 43.83%;
            bottom: 20.83%;
        }
        img.mx-2.icon2 {
            position: absolute;
            right: 0.67%;
            top: 5.67%;
            bottom: 41.67%;
        }
        .icon2 {
            position: absolute;
            right: 0.67%;
            top: 0.67%;
/*            bottom: 41.67%;*/
        }
        .delete-option {
           position: absolute;
            left: 70.62%;
            /* right: 20.6%; */
            /* top: 52.25%; */
            bottom: 47.42%;
            background: #FFFFFF;
            box-shadow: 0px 0.761905px 1.52381px rgb(0 0 0 / 8%);
            border-radius: 9.14286px;
            width: 100px;
        }
        .delete-button{
            cursor: pointer;
            border-radius: 6.09524px;
        }
       
        .delete-button:hover {
/*          background-color: #FFEFEF;*/
        }
      
/*        code for list and grid view.................*/
        .view-group {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: row;
            flex-direction: row;
            padding-left: 0;
            margin-bottom: 0;
        }
        .thumbnail
        {
            margin-bottom: 30px;
            padding: 0px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            border-radius: 8px;
        }

        .item.list-group-item
        {
            float: none;
            width: 100%;
            background-color: #fff;
            margin-bottom: 30px;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 1rem;
            border: 0;
        }
        .item.list-group-item .img-event {
            float: left;
            width: 30%;
        }

        .item.list-group-item .list-group-image
        {
            margin-right: 10px;
        }
        .item.list-group-item .thumbnail
        {
            margin-bottom: 0px;
            display: inline-block;
        }
        .item.list-group-item .caption
        {
            float: left;
            width: 70%;
            margin: 0;
        }

        .item.list-group-item:before, .item.list-group-item:after
        {
            display: table;
            content: " ";
        }

        .item.list-group-item:after
        {
            clear: both;
        }

        .custome-icon {
            position: absolute;
            left: -20px;
            top: 40px;
            z-index: 999;
        }

        .custome-icon i.bi.bi-grip-vertical {
            font-size: 80px;
        }

        .item.list-group-item .thumbnail {
            margin-bottom: 0px !important;
            padding-left: 30px;
        }
        .pull-right.custom-buttons {
            padding-right: 27px;
        }


        .sort-table{
          width: 100%;
          border: 1px solid #cecece;
        }
        
        th{
             font-size: 1em;
            line-height: 1.375em;
            font-weight: 400;
            vertical-align: middle;
            padding: 0.5em 0.9375em;
            text-align: left;
        }
        tr{
          border: 1px solid #cecece;
        }
        td{
          padding: 1em;
          vertical-align: middle;
           display: table-cell;
           border-top: 1px solid #cecece;
        }
        .hidden-td{
          display: none;
        }
       
        table, tr, td, th{
            margin: auto;
            border: none;
        }
        td {
            text-transform: capitalize;
        }
        img.card-img-top {
           max-height: 100px;
            max-width: 100px;
            text-align: center;
            margin: 0px auto;
        }
        .icon2 .dropdown-menu.p-2.ml-2.show {inset: 0px auto auto -85px !important;padding: 0px !important;min-width: auto;}

        .icon2 li.dropdown-item.py-2 {
            min-width: 100px !important;
            width: 100px !important;
        }
        .text-right{
            text-align:right;
        }
        .toast-message{
            color:#14BC9A;
        }
        .modal-body {
            padding: 0rem !important;
        }

        .datepicker table td img, .table td img {
            width: auto !important;
            height: 40px !important;
            border-radius: 0 !important;
        }

        .table-responsive {
            padding: 0px 42px 10px 35px;
        }
    </style>
@endpush

@section('content')
<div class="container1">
        @php
            $satusUrl =app('request')->input('status');
            $gridStatus = app('request')->input('grid-status');
        @endphp
        <div class="row">
           <!--col-->
            @can('update_featured_logo')
            <div class="col-lg-12 my-3">
                <div class="pull-right custom-buttons">
                    <div class="btn-group">
                        <a href="#" class="rearrange-status" ><button type="button" class="btn btn-outline-success btn-sm rearrange">{{$satusUrl==='rearrange' ? 'Exit':'Rearrange'}}</button></a>
                    </div>
                </div>
            </div>
            @endcan
        <div id="serializeForm" >
            <div id="clogos" class="row view-group">
                <div class="row grid-example " id="{{$satusUrl==='rearrange' ? 'grid-example':''}}">
                @for ($i = 0; $i < 18; $i++)
                        @php
                            $is_logo = false;
                            $j = -1;
                        @endphp
                       <div class="col-md-2 column rearrange{{$i}} item" id="{{$i}}" >
                            <div class="custome-icon" style="display:none;">
                                <i class='bi bi-grip-vertical'></i>
                            </div>
                            <div class="card  mb-4 thumbnail hand-hover" style="min-height: 166px;width:100% border-radius: 8px;">
                                @if($satusUrl==='rearrange')
                                    <div class="mt-2 ml-2 text-right" style="margin-right: 5px;" ><i class="fa fa-bars" aria-hidden="true"></i></div>
                                @endif
                                @foreach ($logos as $key => $logo)
                                    @if ($logo->order == $i + 1)
                                        @php
                                            $is_logo = true;
                                            $j = $key;
                                        @endphp
                                    @endif
                                @endforeach
                                @if ($is_logo && isset($logos[$j]))
                                   
                                    <div class="card-body">
                                    <div style="min-height: 100px; display:flex; align-items:center;">
                                        <img class="card-img-top" src="{{ isset($logos[$j]->company) ? $logos[$j]->company->logo : '' }}"
                                            alt="Card image cap">
                                    </div>
                                        <input type="hidden" name="CompanyLogoId[]" value="{{$logos[$j]->id}}" id="CompanyLogoId{{$i}}" data-CompanyLogoId="{{$logos[$j]->id}}">
                                        <input type="hidden" name="joborder[]" value="{{$i}}" id="joborder{{$i}}" >
                                    @if($satusUrl !=='rearrange' )
                                    @canany('delete_featured_logo','update_featured_logo')

                                    <ul class="navbar-nav">
                                        <li class="nav-item dropdown icon2">
                                            <img src="{{ asset('img/job2.png') }}" alt="" class="mx-2  nav-link dropdown-toggle" style="max-width: 140px;height: auto;" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-id="{{$j}}" id="profileDropdown">
                                            <div class="dropdown-menu p-2 ml-2" aria-labelledby="profileDropdown">
                                             
                                              <ul class="list-unstyled p-1">
                                                @can('update_featured_logo')
                                                 <li class="dropdown-item py-2">
                                                    <div class="add-logo"  data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{$i+1}}">
                                                        Replace
                                                     <input type="hidden" value="{{$i}}" id="joborder{{$i}}" >
                                                 </div>
                                                </li>
                                                @endcan
                                                @can('delete_featured_logo')
                                                <li class="dropdown-item py-2">
                                                    <div class="delete-button" jid="{{ $logos[$j]->id }}">Delete </div>
                                                        <div class="menu-options mt-2"> </div>
                                                </li>
                                                @endcan
                                              </ul>
                                            </div>
                                          </li>
                                    </ul>
                                     @endcanany
                                    @endif

                                    @else
                                     <div class="card-body card-body" >
                                        @can('add_featured_logo')
                                            <span class="add-logo"  style="min-height: 150px;width:100%" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{$i+1}}">
                                                <img src="{{ asset('img/job.png') }}" alt="" class="mx-2 icon" style="max-width: 140px;height: auto;">
                                                 <input type="hidden" value="{{$i}}" id="joborder{{$i}}" >
                                             </span>
                                        @endcan
                                    @endif
                                    </div>
                                </div>
                            </div>
                            
                        @endfor
                        {{-- Model --}}
                        <div class="modal fade add-job " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                              </div>
                               <div class="col-sm-12 my-2" >
                                    <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                                       <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                                            <div class="input-group">
                                                  <div class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                  </div>
                                                <input type="text" class="form-control" name="keyword" id="search" placeholder="Search here..."  value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
                                            </div>
                                            
                                        </div><!--btn-toolbar--> 
                                    </div>
                                 </div>
                                    <div class="modal-body ">
                                      <input type="hidden" name="order" value="{{$i+1}}"> 
                                      <div class="row mt-4 mb-4">
                                        <div class="col">
                                            <div class="form-group row">
                                                <div class="table-responsive">
                                                     <table class="example-table1 table" id="">
                                                      <thead>
                                                        <tr>
                                                          <th></th>
                                                          <th>Logo</th>
                                                          <th data-name="name" class="sortable">Companies Name</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody id="table-data">
                                                      </tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                      </div> 
                                        <div class="row" id="table-pagination" style="margin-left: 30px !important;"> </div>
                                   </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary checked-data" id="checked-data">Save</button>
                                  </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List view --}}
        <div class="row list-view" style="display:none;">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" >
                    <thead>
                      <tr>
                          <th class="">#</th>
                          <th class="">Slot</th>
                          <th class="">Logo</th>
                          <th class="">URL</th>
                        </tr>
                    </thead>
                     <tbody  id="{{$satusUrl==='rearrange' ? 'list-grid-example':''}}">
                       @for ($i = 0; $i < 16; $i++)
                            @php
                                $is_logo = false;
                                $j = -1;
                            @endphp
                            @foreach ($logos as $key => $logo)
                                @if ($logo->order == $i + 1)
                                    @php
                                        $is_logo = true;
                                        $j = $key;
                                    @endphp
                                @endif
                            @endforeach
                            @if ($is_logo && isset($logos[$j]))
                                <tr id="{{$i}}">
                                    <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                    <td class=""> {{$i+1}} </td>
                                    <td class="">  <img src="{{ $logos[$j]->logo_url ?  $logos[$j]->logo_url : asset('img/job-logo.png') }}" alt="Logo" class="mx-2"style="width: 50; height: 50;"> {{ isset($jobs[$j]) ? $jobs[$j]->company_name : '' }}</td>
                                    <td class="">  {{ isset($logos[$j]) ? $logos[$j]->website_link : '' }}</td>
                                </tr>
                                 <input type="hidden" name="CompanyLogoId[]" value="{{$logos[$j]->id}}" id="CompanyLogoId-list-view{{$i}}" data-CompanyLogoId-list-view="{{$logos[$j]->id}}">
                            @else
                                <tr id="{{$i}}">
                                    <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                </tr>
                                 <input type="hidden" name="CompanyLogoId[]" value="" id="CompanyLogoId-list-view{{$i}}" data-CompanyLogoId-list-view="">
                            @endif
                        @endfor
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
     <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.img-priview').show()
                    $('#logo-preview')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('.delete-button').on('click', function(){
        let id = $(this).attr('jid');
        const self = $(this);
        axios.post(`{{route("admin.auth.homepage-logos.delete")}}`, {'id': id})
             .then((resp) => {
                SwalMessage('Logo deleted successfully!','success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
             })
             .catch((err) => {
                console.log(err);
             })
        
    });
    $('.icon2').on('click',function(){ 
        let id = $(this).attr('data-id');
        $('.delete-operation'+id).toggle();
    });

    var satusUrl = "{{$satusUrl}}"
    var gridStatus = "{{$gridStatus}}"
    if(gridStatus ==='listview'){
        $('#clogos .item').addClass('list-group-item');
        $('.card-footer').css('display','none')
        $('.posted-date').css('display','block')
        $('.custome-icon').css('display','block')
        $('.delete-option').css('left','93.62%')
        $('.thumbnail').css('min-height','160px')
        $('.thumbnail').css('margin-left','-16px')
        $('.thumbnail').css('width','102%')
        $('.custom-buttons').css('padding-right','15px')
        $('#serializeForm').css('display','none')
        $('.list-view').css('display','block')
    }
    var baseUrl = window.location.origin+'/admin/auth/homepage-logos'
    $('#list').click(function(event){
        var currentUrl = baseUrl +'?grid-status=listview'
        window.location.replace(currentUrl);
        if(satusUrl ==='rearrange'){
            var currentUrl = baseUrl +'?status=rearrange&grid-status=listview'
            window.location.replace(currentUrl);
        }
    });
    $('#grid').click(function(event){
        // event.preventDefault();
        $('#clogos .item').removeClass('list-group-item');
        $('#clogos .item').addClass('grid-group-item');
        if(satusUrl ==='rearrange'){
            $('#clogos .item').addClass('list-group-item');
            var currentUrl = baseUrl +'?status=rearrange&grid-status=gridview'
            window.location.replace(currentUrl);
        }
        if(gridStatus ==='listview' && satusUrl !=='rearrange'){
            $('#clogos .item').addClass('list-group-item');
            var currentUrl = baseUrl +''
            window.location.replace(currentUrl);
        }
    });
    $('.rearrange-status').on('click',function(){
        $('.grid-example').attr('id','grid-example')
        var currentUrl = baseUrl +'?status=rearrange'
        window.location.replace(currentUrl);
    })

    $('.rearrange-status').on('click',function(){
     var satusUrl = "{{$satusUrl}}"
        if(satusUrl ==='rearrange'){
            var currentUrl = baseUrl
            window.location.replace(currentUrl);
        }
        
    })

// npm package: sortablejs
// github link: https://github.com/SortableJS/Sortable

$(function() {
  'use strict';

  // Grid example
  if ($("#grid-example").length) {

    var gridExample = document.querySelector("#grid-example");
    new Sortable(gridExample, {
     onUpdate: function (/**Event*/evt, /**Event*/originalEvent) {
        var result = $('.column').sortable("serialize");
        var ids = [];
        $.each(result, function(key,val) {
            var newCompanyLogoId = $('#CompanyLogoId'+val.id).attr('data-CompanyLogoId');
            ids.push(newCompanyLogoId);
        });
       
        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.homepage-logos.logo_order_change') }}",
          data: {ids:ids},
          success: function(data)
          {
            SwalMessage('Logo rearrange successfully!');
            location.reload();
          }
        });
    },
  })
}
 // list Grid example
  if ($("#list-grid-example").length) {
    var gridExample = document.querySelector("#list-grid-example");
    new Sortable(gridExample, {
    
     onMove: function (/**Event*/evt, /**Event*/originalEvent) {
     
        var oldOrderId = evt.dragged.id
        var newOrderId = evt.related.id
        var oldCompanyLogoId = $('#CompanyLogoId-list-view'+evt.dragged.id).attr('data-CompanyLogoId-list-view')
        var newCompanyLogoId = $('#CompanyLogoId-list-view'+evt.related.id).attr('data-CompanyLogoId-list-view')

        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.homepage-logos.logo_order_change') }}",
          data: {oldOrderId:oldOrderId,newOrderId:newOrderId,oldCompanyLogoId:oldCompanyLogoId,newCompanyLogoId:newCompanyLogoId},
          success: function(data)
          {
            // location.reload();

          }
        });
    },
  })
}
function SwalMessage(message){
   
     const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });
      
      Toast.fire({
        icon: 'success',
        title: message
      })
}
// Nested example
if ($("#nested-sortable").length) {
    var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
        // Loop through each nested sortable element
        for (var i = 0; i < nestedSortables.length; i++) {
          new Sortable(nestedSortables[i], {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65
          });
        }
      }

});

let order = '';
$('.add-logo').on('click',function(){ 
    order = $(this).attr('data-id');
    getLogosCompanyList();
});
//custom pagination...................
    function getLogosCompanyList(params='',$url='',order_id) {
        $('body').find('.table').addClass('loading');
        var url = $url != '' ? $url : "{{ route('admin.auth.homepage-logos.logos-company-list',['type'=>'']) }}"+order+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.table').removeClass('loading');
                if (data.status == true) {
                    $('#table-data').html(data.table);
                    $('body').find('#table-pagination').html(data.pagination);
                }
            },
        });
    }

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getLogosCompanyList('',url);
    });

   
    var $sortable = $('body').find('.sortable');
    $('body').on('click', '.sortable', function() {
      
        var $this = $(this);
        var asc = $this.hasClass('asc');
        var desc = $this.hasClass('desc');
        var sort_column = $this.attr('data-name');
        $this.removeClass('asc').removeClass('desc');
        if (desc || (!asc && !desc)) {
            getLogosCompanyList('&column='+sort_column+'&sort=asc');
            $this.addClass('asc');
        } else {
            getLogosCompanyList('&column='+sort_column+'&sort=desc');
            $this.addClass('desc');
        }
          
    });
    $('#search').keyup(function(e) {
        var q = $(this).val();
        getLogosCompanyList('&q='+q);
    });

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   
$('.checked-data').on('click',function(){
     if($('input[name="selected_job"]:checked').val() !=undefined){
        let company_id = $('input[name="selected_job"]:checked').val();
        let order = $('input[name="selected_job"]:checked').attr('data-order');
        var url = "{{ route('admin.auth.homepage-logos.store-company-logo') }}";
        $.ajax({
           type:'POST',
           url:url,
           data:{company_id:company_id,order:order},
           success:function(resp){
                if(resp==='replace'){
                    SwalMessage('Featured logo replaced successfully!','success');
                }else{
                    SwalMessage('Featured logo added successfully!','success');
                }
                setTimeout(function() {
                    location.reload();
                }, 1500);
           }
        });
    }
});

});
</script>
{{-- @endpush --}}
