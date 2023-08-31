@extends('backend.layouts.app')

@section('title', app_name() . ' | Featured gigs Management')

@push('after-styles')
    {{-- <link href="{{ asset('assets/admin-homepage-settings.css') }}" rel="stylesheet" /> --}}
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
            top: 4.67%;
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
           max-height: 115px;
            max-width: 115px;
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
        .card-footer.bg-transparent {
            font-size: small;
        }
    </style>

@endpush

@section('content')
    <div class="container1">
    @php
        $satusUrl =app('request')->input('status');
        $gridStatus =app('request')->input('grid-status');
    @endphp
      <div class="row">
        <!--col-->
        <div class="col-md-12 my-3" >
            <div class="btn-toolbar pull-right custom-buttons" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
               <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                    <button class="btn btn-outline-primary btn-sm" id="list" ><i class="fa fa-bars"></i></button>&nbsp;
                    <button class="btn btn-outline-primary btn-sm" id="grid"><i class="fa fa-th-large "></i></button>&nbsp;
                    @can('update_featured_gigs')
                    <a href="#" class="rearrange-status" ><button type="button" class="btn btn-outline-success btn-sm rearrange">{{$satusUrl==='rearrange' ? 'Exit':'Rearrange'}}</button></a>
                    @endcan
                </div><!--btn-toolbar--> 
            </div>
        </div>
        <div id="serializeForm" >
        <div id="products" class="row view-group">
            <div class="row grid-example " id="{{$satusUrl==='rearrange' ? 'grid-example':''}}">
                    @for($i = 0; $i < 18; $i++)
                        @php
                            $is_gig = false;
                            $j = -1;
                        @endphp
                        
                         <div class="col-md-3 column rearrange{{$i}} item" id="{{$i}}" >
                            <div class="custome-icon" style="display:none;">
                                <i class='bi bi-grip-vertical' style="color:#9398A1"></i>
                            </div>
                            <div class="card  mb-4 thumbnail" style="min-height: 190px;width:100% border-radius: 8px;">
                                @if($satusUrl==='rearrange')
                                    <div class="mt-3 ml-2 text-right" style="margin-right: 10px;" ><i class="fa fa-bars" aria-hidden="true"></i></div>
                                @endif
                                <div class="card-body caption">
                                @foreach($featuredGigs AS $key => $featuredGig)
                                    @if($featuredGig->order == $i + 1)
                                        @php
                                            $is_gig = true;
                                            $j = @$key;
                                        @endphp
                                    @endif
                                    @endforeach
                                    @if($is_gig && isset($featuredGigs[$j]))
                                        <h6 class="card-title mb-2 text-muted " style="margin-left: -10px;">
                                            <img src="{{ ($featuredGigs[$j]->company && $featuredGigs[$j]->company->logo) ?  $featuredGigs[$j]->company->logo : '' }}"  style="max-width: 30px;">
                                        </h6>
                                        <div class="card-content">
                                            <h6 class="card-subtitle truncate-1 mt-3">{{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->title : '' }}</h6>
                                             <span class="card-subtitle truncate-1 mt-1" style="color: #9398A1;  font-size: smaller;">{{ isset($featuredGigs[$j]->type) ? $featuredGigs[$j]->type->name : '' }}</span>
                                            <h6 class="card-subtitle truncate-1 mt-1" style=" font-size: smaller;"> <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i> {{ isset($featuredGigs[$j]->locations) ? @$featuredGigs[$j]->locations[0]->name : '' }}</a></h6>
                                            <p class="card-text mt-2 posted-date" style="display: none;">{{ isset($featuredGigs[$j]) ? date("d-m-Y", strtotime($featuredGigs[$j]->created_at)) : '' }}</p>
                                        </div>
                                        <input type="hidden" name="gigId[]" value="{{$featuredGigs[$j]->id}}" id="gigId{{$i}}" data-gigid="{{$featuredGigs[$j]->id}}">
                                        <input type="hidden" name="gigorder[]" value="{{$i}}" id="gigorder{{$i}}" data-gigorderid="{{$i}}">
                                        
                                    @if($satusUrl !=='rearrange')
                                        @can('delete_featured_gigs')
                                            <ul class="navbar-nav">
                                                <li class="nav-item dropdown icon2">
                                                    <img src="{{ asset('img/job2.png') }}" alt="" class="mx-2  nav-link dropdown-toggle" style="max-width: 140px;height: auto;" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-id="{{$j}}" id="profileDropdown">
                                                    <div class="dropdown-menu p-2 ml-2" aria-labelledby="profileDropdown">
                                                     
                                                      <ul class="list-unstyled p-1">
                                                        <li class="dropdown-item py-2">
                                                          {{-- <a href="#" class="text-body ms-0" class="delete-button1" jid="{{ $featuredGigs[$j]->id }}"> --}}
                                                            <div class="delete-button" jid="{{ $featuredGigs[$j]->id }}">Delete </div>
                                                                <div class="menu-options mt-2"> </div>
                                                          {{-- </a> --}}
                                                        </li>
                                                      </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endcan
                                    @endif
                                    @else
                                    @can('add_featured_gigs')
                                    <img src="{{ asset('img/job.png') }}" alt="" class="mx-2 icon" style="max-width: 140px;height: auto;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{$i+1}}">
                                     <input type="hidden" value="{{$i}}" id="gigorder{{$i}}" >
                                     <input type="hidden" name="gigorder[]" value="{{$i+1}}" id="gigorder{{$i}}" data-gigorderid="{{$i}}">
                                     @endcan
                                @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <span class="card-text">{{ isset($featuredGigs[$j]) ? substr( @$featuredGigs[$j]->company->name, 0, 17)."" : '' }}</span>
                                    <span class="card-text test" style="float: right;">{{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->created_at->diffForHumans() : '' }}</span>
                                </div>
                          </div>
                        </div>
                       
                    @endfor
                     <!-- Modal -->
                      <div class="modal fade add-gig" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Select Gig</h5>
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
                                    {{-- @include('backend.auth.user.includes.search-field') --}}
                                </div>
                              <div class="modal-body">
                                  <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="form-group row">
                                            <div class="table-responsive">
                                                <table class="example-table1 table" id="">
                                                  <thead>
                                                    <tr>
                                                      <th data-name="id" class="sortable id">#</th>
                                                      <th data-name="name" class="sortable name">Companies Name</th>
                                                      <th data-name="title" class="sortable title">Gig Title</th>
                                                      <th data-name="created_at" class="sortable created_at">Posted On</th>
                                                      <th data-name="first_name" class="sortable first_name">Posted By</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody id="table-data">
                                                     
                                                  </tbody>
                                                </table>
                                            </div>
                                             <div class="row" id="table-pagination" style="margin-left: 30px !important;"> </div>
                                        </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary checked-data" id="checked-data">Save</button>
                              </div>
                            </div>
                          </div>
                        </div> 
                </div>
           
            </div>
        </div>  
     </div>
    
    <div class="row list-view" style="display:none;">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" >
                <thead>
                  <tr>
                    @if($gridStatus !=='listview'  || $satusUrl==='rearrange')
                      <th class=""></th>
                      {{-- <th class="">Slot</th> --}}
                    @endif
                      <th class="">Gig ID</th>
                      <th class="">Companies Name</th>
                      <th class="">Gig Title</th>
                      <th class="">Posted On</th>
                      {{-- <th class="">Time Remaining</th> --}}
                      <th class="">Posted By</th>
                    </tr>
                </thead>
                 <tbody  id="{{$satusUrl==='rearrange' ? 'list-grid-example':''}}"  >
                    @for($i = 0; $i < 18; $i++)
                        @php
                            $is_gig = false;
                            $j = -1;
                        @endphp
                        @foreach($featuredGigs AS $key => $featuredGig)
                            @if($featuredGig->order == $i + 1)
                                @php
                                    $is_gig = true;
                                    $j = @$key;
                                @endphp
                            @endif
                        @endforeach
                        @if($is_gig && isset($featuredGigs[$j]))
                            <tr id="{{$i}}" class="list-view-gig-id"> 
                            @if($gridStatus !=='listview' || $satusUrl==='rearrange')
                                <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                {{-- <td class="" id="slot{{$i+1}} "> {{$i+1}} </td> --}}
                            @endif
                                <td class="">  {{$featuredGigs[$j]->id}}</td>
                                <td class="">  
                                    {{ isset($featuredGigs[$j]->company) ? $featuredGigs[$j]->company->name : '' }}</td>
                                <td class="">  {{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->title : '' }}</td>
                                <td class="">  {{date("d-m-Y", strtotime($featuredGigs[$j]->created_at))}}</td>
                                <td class="">  {{ isset($featuredGigs[$j]) ? @$featuredGigs[$j]->user->first_name .' '.@$featuredGigs[$j]->user->last_name : '' }}</td>
                            </tr>
                             <input type="hidden" name="gigId[]" value="{{$featuredGigs[$j]->id}}" id="gigId-list-view{{$i}}" data-gigid-list-view="{{$featuredGigs[$j]->id}}">
                        @else
                            @if($satusUrl==='rearrange')
                                <tr id="{{$i}}" class="list-view-gig-id">
                                @if($gridStatus !=='listview' || $satusUrl==='rearrange')
                                    <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                @endif
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                    <td class="">  </td>
                                </tr>
                                 <input type="hidden" name="gigId[]" value="" id="gigId-list-view{{$i}}" data-gigid-list-view="">
                             @endif
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
 @endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('assets/plugins/jquery-ui-dist/jquery-ui.min.js') }}"></script>

<script type="text/javascript">
   
$(document).ready(function() {
    var satusUrl = "{{$satusUrl}}"
    var gridStatus = "{{$gridStatus}}"
    if(gridStatus ==='listview'){
        $('#products .item').addClass('list-group-item');
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
    var baseUrl = window.location.origin+'/admin/auth/featured-gigs'
    $('#list').click(function(event){
        var currentUrl = baseUrl +'?grid-status=listview'
        window.location.replace(currentUrl);
        if(satusUrl ==='rearrange'){
            var currentUrl = baseUrl +'?status=rearrange&grid-status=listview'
            window.location.replace(currentUrl);
        }
    });
    $('#grid').click(function(event){
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
        if(satusUrl ==='rearrange'){
            $('#products .item').addClass('list-group-item');
            var currentUrl = baseUrl +'?status=rearrange&grid-status=gridview'
            window.location.replace(currentUrl);
        }
        if(gridStatus ==='listview' && satusUrl !=='rearrange'){
            $('#products .item').addClass('list-group-item');
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
            $('.caption').css('margin-top','-40px')
            window.location.replace(currentUrl);

        }
        
    })
    if(satusUrl ==='rearrange'){
        $('.caption').css('margin-top','-40px')
    }else{
        $('.caption').css('margin-top','-10px')
    }

// npm package: sortablejs
// github link: https://github.com/SortableJS/Sortable

$(function() {
  'use strict';


  // Simple list example
  if ($("#simple-list").length) {
    var simpleList = document.querySelector("#simple-list");
    new Sortable(simpleList, {
      animation: 150,
      ghostClass: 'bg-light'
    });
  }



  // Handle example
  if ($("#handle-example").length) {
    var handleExample = document.querySelector("#handle-example");
    new Sortable(handleExample, {
      handle: '.handle', // handle's class
      animation: 150,
      ghostClass: 'bg-light'
    });
  }



  // Shared lists example
  if ($("#shared-list-left").length) {
    var sharedListLeft = document.querySelector("#shared-list-left");
    new Sortable(sharedListLeft, {
      group: 'shared', // set both lists to same group
      animation: 150,
      ghostClass: 'bg-light'
    });
  }
  if ($("#shared-list-right").length) {
    var sharedListRight = document.querySelector("#shared-list-right");
    new Sortable(sharedListRight, {
      group: 'shared', // set both lists to same group
      animation: 150,
      ghostClass: 'bg-light'
    });
  }



  // Cloning example
  if ($("#shared-list-2-left").length) {
    var sharedList2Left = document.querySelector("#shared-list-2-left");
    new Sortable(sharedList2Left, {
      group: {
        name: 'shared2',
        pull: 'clone' // To clone: set pull to 'clone'
      },
      animation: 150,
      ghostClass: 'bg-light'
    });
  }
  if ($("#shared-list-2-right").length) {
    var sharedList2Right = document.querySelector("#shared-list-2-right");
    new Sortable(sharedList2Right, {
      group: {
        name: 'shared2',
        pull: 'clone' // To clone: set pull to 'clone'
      },
      animation: 150,
      ghostClass: 'bg-light'
    });
  }



  // Disabling sorting example
  if ($("#shared-list-3-left").length) {
    var sharedList3Left = document.querySelector("#shared-list-3-left");
    new Sortable(sharedList3Left, {
      group: {
        name: 'shared3',
        pull: 'clone',
        put: false // Do not allow items to be put into this list
      },
      animation: 150,
      ghostClass: 'bg-light',
      sort: false // To disable sorting: set sort to false
    });
  }
  if ($("#shared-list-3-right").length) {
    var sharedList3Right = document.querySelector("#shared-list-3-right");
    new Sortable(sharedList3Right, {
      group: {
        name: 'shared3',
      },
      animation: 150,
      ghostClass: 'bg-light'
    });
  }


  
  // Filter example
  if ($("#filter-example").length) {
    var filterExample = document.querySelector("#filter-example");
    new Sortable(filterExample, {
      filter: '.filtered', // 'filtered' class is not draggable
      animation: 150,
      ghostClass: 'bg-light'
    });
  }



  // Grid example
  
  if ($("#grid-example").length) {
    var gridExample = document.querySelector("#grid-example");
    new Sortable(gridExample, {
     onUpdate: function (/**Event*/evt, /**Event*/originalEvent) {
        var result = $('.column').sortable("serialize");
        var ids = [];
          $.each(result, function(key,val) {
            var newCompanyLogoId = $('#gigId'+val.id).attr('data-gigid');
           ids.push(newCompanyLogoId);
        });
        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.featured-gigs.gig_order_change') }}",
          data: {ids:ids},
          success: function(data)
          {
            SwalMessage('Gig rearrange successfully!');
            location.reload();
          },error: function(errors){
            SwalMessage('Permission deny!','warning');
          }
        });
    },
  })
}

 // list Grid example
  if ($("#list-grid-example").length) {
    var gridExample = document.querySelector("#list-grid-example");
    new Sortable(gridExample, {
     onUpdate: function (/**Event*/evt, /**Event*/originalEvent) {
        var result = $('.list-view-gig-id').sortable("serialize");
        var ids = [];
          $.each(result, function(key,val) {
            var newCompanyLogoId = $('#gigId-list-view'+val.id).attr('data-gigid-list-view');
           ids.push(newCompanyLogoId);
        });
          console.log(ids)
        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.featured-gigs.gig_order_change') }}",
          data: {ids:ids},
          success: function(data)
          {
           SwalMessage('Gig rearrange successfully!');
           setTimeout(function() {
            }, 1500);

          },error: function(errors){
            SwalMessage('Permission deny!','warning');
          }
        });
    },
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
    

$('.delete-button').on('click', function(){
    // let ld = Ladda.create(this);
    let id = $(this).attr('jid');
    const self = $(this);
    // ld.start();
    axios.post(`{{route("admin.auth.featured-gigs.delete")}}`, {'id': id})
         .then((resp) => {
            SwalMessage('Gig deleted successfully!','success');
            setTimeout(function() {
                location.reload();
            }, 1500);
         })
         .catch((err) => {
            console.log(err);
     })
    
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   
$('.checked-data').on('click',function(){
     if($('input[name="selected_gig"]:checked').val() !=undefined){
        let gig_id = $('input[name="selected_gig"]:checked').val();
        let order = $('input[name="selected_gig"]:checked').attr('data-order');
        var url = "{{ route('admin.auth.featured-gigs.store') }}";
        $.ajax({
           type:'POST',
           url:url,
           data:{gig_ids:gig_id,order:order},
           success:function(resp){
                SwalMessage('Gig added successfully!');
                setTimeout(function() {
                    location.reload();
                }, 1500);
           }
        });
    }
});
   
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
$('.icon2').on('click',function(){ 
    let id = $(this).attr('data-id');
    $('.delete-operation'+id).toggle();
});

$('.icon').on('click',function(){ 
    let order_id = $(this).attr('data-id');
       getAllGigsList(order_id);
});

//custom pagination...................
    function getAllGigsList(params='',$url='',order_id) {
        $('body').find('.table').addClass('loading');
        var url = $url != '' ? $url : "{{ route('admin.auth.featured-gigs.all-gigs-list',['type'=>'']) }}"+params;
        $.ajax({
            type: "get",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('body').find('.table').removeClass('loading');
                if (data.status == true) {
                    console.log(data.table)
                    $('#table-data').html(data.table);
                    $('body').find('#table-pagination').html(data.pagination);
                }
            },
        });
    }

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getAllGigsList('',url);
    });

   
    var $sortable = $('body').find('.sortable');
    $('body').on('click', '.sortable', function() {
      
        var $this = $(this);
        var asc = $this.hasClass('asc');
        var desc = $this.hasClass('desc');
        var sort_column = $this.attr('data-name');
        $this.removeClass('asc').removeClass('desc');
        if (desc || (!asc && !desc)) {
            getAllGigsList('&column='+sort_column+'&sort=asc');
            $this.addClass('asc');
        } else {
            getAllGigsList('&column='+sort_column+'&sort=desc');
            $this.addClass('desc');
        }
          
    });
    $('#search').keyup(function(e) {
    // if (e.keyCode == 13) {
        var q = $(this).val();
        getAllGigsList('&q='+q);
    // }
    });

});

</script>
