@extends('backend.layouts.app')

@section('title', app_name() . ' | Featured Gigs Management')

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    {{-- <link href="{{ asset('assets/css/admin-homepage-settings.css') }}" rel="stylesheet" /> --}}
    <style type="text/css">
        img.mx-2.icon {
            position: absolute;
            left: 43.83%;
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
        .delete-option {
           position: absolute;
            left: 53.62%;
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
        /*.col-md-2 {
            flex: 0 0 auto;
            width: 15.666667%;
        }*/
        .delete-button:hover {
          background-color: #FFEFEF;
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
            border-radius: 0px;
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
    </style>
@endpush

@section('content')
    <div class="container1">
    @php
        $satusUrl =app('request')->input('status');
        $gridStatus =app('request')->input('grid-status');
    @endphp
      <div class="row">
        
        <div class="col-lg-12 my-3">
            <div class="pull-right custom-buttons">
                <div class="btn-group">
                        <button class="btn btn-outline-primary btn-lg" id="list" ><i class="fa fa-bars"></i></button>&nbsp;
                        <button class="btn btn-outline-primary btn-lg" id="grid"><i class="fa fa-th-large "></i></button>&nbsp;
                    <a href="#" class="rearrange-status" ><button type="button" class="btn btn-outline-success btn-sm rearrange">{{$satusUrl==='rearrange' ? 'Exit':'Rearrange'}}</button></a>
                </div>
            </div>
        </div>
        <div id="serializeForm" >
        <div id="products" class="row view-group">
            <div class="row grid-example " id="{{$satusUrl==='rearrange' ? 'grid-example':''}}">
                    @for($i = 0; $i < 18; $i++)
                        @php
                            $is_job = false;
                            $j = -1;
                        @endphp
                        
                         <div class="col-md-2 column rearrange{{$i}} item" id="{{$i}}" >
                            <div class="custome-icon" style="display:none;">
                                <i class='bi bi-grip-vertical'></i>
                            </div>
                            <div class="card  mb-4 thumbnail" style="min-height: 235px;width:100%">
                                {{-- style="min-height: 245px; margin-bottom: 10%; width: 245px;" --}}
                                 @if($satusUrl==='rearrange')
                                    <div class="mt-2 ml-2 " style="margin-left: 5px;" ><i class="fa fa-bars" aria-hidden="true"></i></div>
                                @endif
                                <div class="card-body caption">
                                @foreach($featuredGigs AS $key => $featuredGig)
                                    @if($featuredGig->order == $i + 1)
                                        @php
                                            $is_job = true;
                                            $j = $key;
                                        @endphp
                                    @endif
                                    @endforeach
                                    @if($is_job && isset($featuredGigs[$j]))
                                       
                                        <h6 class="card-title mb-2 text-muted truncate-1 " style="margin-left: -8px;"><img src="{{ $featuredGigs[$j]->logo ?  asset($featuredGigs[$j]->logo) : asset('img/job-logo.png') }}" alt="Logo" class="mx-2"style="max-width: 20px;">{{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->company_gigs_details->company_name : '' }}</h6>
                                        <h6 class="card-subtitle truncate-1 mt-3">{{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->title : '' }}</h6>
                                        <p class="card-text mt-2">Location:</p>
                                        <p class="card-text mt-2 posted-date" style="display: none;">{{ isset($featuredGigs[$j]) ? date("d-m-Y", strtotime($featuredGigs[$j]->created_at)) : '' }}</p>
                                        <img src="{{ asset('img/job2.png') }}" alt="" class="mx-2 icon2" style="max-width: 140px;height: auto;" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-id="{{$j}}">
                                        <input type="hidden" name="jobId[]" value="{{$featuredGigs[$j]->gig_id}}" id="jobId{{$i}}" data-jobid="{{$featuredGigs[$j]->gig_id}}">
                                        <input type="hidden" name="joborder[]" value="{{$i+1}}" id="joborder{{$i}}" >
                                        <div class="delete-option delete-operation{{$j}}" style="display:none;">
                                            <div class="card " >
                                                <div class="card-body delete-operation-card mb-3">
                                                    <div class="row">
                                                        <div class="delete-button" jid="{{ $featuredGigs[$j]->id }}">Delete </div>
                                                        <div class="menu-options mt-2"> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    <img src="{{ asset('img/job.png') }}" alt="" class="mx-2 icon" style="max-width: 140px;height: auto;" data-bs-toggle="modal" data-bs-target="#exampleModal{{$i+1}}">
                                     <input type="hidden" value="{{$i}}" id="joborder{{$i}}" >
                                     <input type="hidden" name="joborder[]" value="{{$i+1}}" id="joborder{{$i}}" >
                                @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <span class="card-text">{{ isset($featuredGigs[$j]) ? substr($featuredGigs[$j]->company_gigs_details->company_name, 0, 8)."." : '' }}</span>
                                    <span class="card-text test" style="float: right;">{{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->created_at->diffForHumans() : '' }}</span>
                                </div>
                          </div>
                        </div>
                    @endfor
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
                      <th class="">#</th>
                      <th class="">Slot</th>
                      <th class="">Job ID</th>
                      <th class="">Companies Name</th>
                      <th class="">Job Title</th>
                      <th class="">Posted On</th>
                      <th class="">Time Remaining</th>
                      <th class="">Posted By</th>
                    </tr>
                </thead>
                 <tbody  id="{{$satusUrl==='rearrange' ? 'list-grid-example':''}}">
                    @for($i = 0; $i < 18; $i++)
                        @php
                            $is_job = false;
                            $j = -1;
                        @endphp
                        @foreach($featuredGigs AS $key => $featuredGig)
                            @if($featuredGig->skills == $i + 1)
                                @php
                                    $is_job = true;
                                    $j = $key;
                                @endphp
                            @endif
                        @endforeach
                        @if($is_job && isset($featuredGigs[$j]))
                            <tr id="{{$i}}">
                                <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                <td class=""> {{$i+1}} </td>
                                <td class="">  {{$featuredGigs[$j]->job_id}}</td>
                                <td class="">  <img src="{{ $featuredGigs[$j]->logo ?  asset($featuredGigs[$j]->logo) : asset('img/job-logo.png') }}" alt="Logo" class="mx-2"style="max-width: 20px; max-height: 20px;"> {{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->company_name : '' }}</td>
                                <td class="">  {{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->title : '' }}</td>
                                <td class="">  {{date("d-m-Y", strtotime($featuredGigs[$j]->created_at))}}</td>
                                <td class="">  {{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->created_at->diffForHumans() : '' }}</td>
                                <td class="">  {{ isset($featuredGigs[$j]) ? $featuredGigs[$j]->company_name : '' }}</td>
                            </tr>
                             <input type="hidden" name="jobId[]" value="{{$featuredGigs[$j]->job_id}}" id="jobId-list-view{{$i}}" data-jobid-list-view="{{$featuredGigs[$j]->job_id}}">
                        @else
                            <tr id="{{$i}}">
                                <td class=""> <i class="fa fa-bars" aria-hidden="true"></i> </td>
                                <td class="">  </td>
                                <td class="">  </td>
                                <td class="">  </td>
                                <td class="">  </td>
                                <td class="">  </td>
                                <td class="">  </td>
                                <td class="">  </td>
                            </tr>
                             <input type="hidden" name="jobId[]" value="" id="jobId-list-view{{$i}}" data-jobid-list-view="">
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
{{-- <script src="https://www.nobleui.com/html/template/assets/vendors/sortablejs/Sortable.min.js"></script> --}}
{{-- <script src="https://www.nobleui.com/html/template/assets/js/sortablejs-light.js"></script> --}}
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
    var baseUrl = window.location.origin+'/admin/auth/featured-jobs'
    $('#list').click(function(event){
        // event.preventDefault();
        // $('#products .item').addClass('list-group-item');
        var currentUrl = baseUrl +'?grid-status=listview'
        window.location.replace(currentUrl);
        if(satusUrl ==='rearrange'){
            // $('#products .item').addClass('list-group-item');
            var currentUrl = baseUrl +'?status=rearrange&grid-status=listview'
            window.location.replace(currentUrl);
        }
    });
    $('#grid').click(function(event){
        // event.preventDefault();
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
            window.location.replace(currentUrl);
        }
        
    })

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
    
     onMove: function (/**Event*/evt, /**Event*/originalEvent) {
     
        var oldOrderId = evt.dragged.id
        var newOrderId = evt.related.id
        var oldJobId = $('#jobId'+evt.dragged.id).attr('data-jobid')
        var newJobId = $('#jobId'+evt.related.id).attr('data-jobid')

        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.featured-jobs.order_change') }}",
          data: {oldOrderId:oldOrderId,newOrderId:newOrderId,oldJobId:oldJobId,newJobId:newJobId},
          success: function(data)
          {
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
        var oldJobId = $('#jobId-list-view'+evt.dragged.id).attr('data-jobid-list-view')
        var newJobId = $('#jobId-list-view'+evt.related.id).attr('data-jobid-list-view')

        $.ajax({
          type: "POST",
          url: "{{ route('admin.auth.featured-jobs.order_change') }}",
          data: {oldOrderId:oldOrderId,newOrderId:newOrderId,oldJobId:oldJobId,newJobId:newJobId},
          success: function(data)
          {
            location.reload();

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
    
// $(function(){
//   $("#button").click(function(){
//   var dataString = $("#serializeForm").serialize();
//       $.ajax({
//       type: "POST",
//       url: "{{ route('admin.auth.featured-jobs.order_change') }}",
//       data: dataString,
//       success: function(data)
//       {
//         alert('Success!');
//         $("#serializeForm")[0].reset();
//       }
//       });
//   });
// });

$('.delete-button').on('click', function(){
    // let ld = Ladda.create(this);
    let id = $(this).attr('jid');
    const self = $(this);
    // ld.start();
    axios.post(`{{route("admin.auth.featured-jobs.delete")}}`, {'id': id})
         .then((resp) => {
            if(resp.data == 'error') {
                // toastr.error('Job cannot be deleted. Try-again!', 'System Error');
            }
            else{
                if(resp.data.opt == 1){
                    // toastr.success('Job deleted!', 'Success');
                    location.reload();
                }else{
                    // toastr.info('Job deleted!', 'Success');
                    location.reload();
                }
            }
            // ld.stop();
         })
         .catch((err) => {
            // ld.stop();
            // toastr.error('Job cannot be deleted. Try-again!', 'System Error');
            console.log(err);
         })
    
    // $(this).html('<i class="fas fa-eye-slash"></i>');
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   
$('.checked-data').on('click',function(){
     if($('input[name="selected_job"]:checked').val() !=undefined){
        let job_id = $('input[name="selected_job"]:checked').val();
        let order = $('input[name="selected_job"]:checked').attr('data-order');
        var url = "{{ route('admin.auth.featured-jobs.store') }}";
        $.ajax({
           type:'POST',
           url:url,
           data:{job_id:job_id,order:order},
           success:function(resp){
               if(resp == 'error'){
                    Swal.fire({
                      icon: 'error',
                      title: 'Job cannot be added. Try-again!',
                      showConfirmButton: false,
                      timer: 1500
                    })
                }else{
                    if(resp == 'success'){
                        $('.add-job').modal('hide');
                       Swal.fire({
                          icon: 'success',
                          title: 'Job added!',
                          showConfirmButton: false,
                          timer: 1500
                        })
                        location.reload();
                    }else{
                        $('.add-job').modal('hide');
                        Swal.fire({
                          icon: 'success',
                          title: 'Job added!',
                          timer: 1500
                        })
                        location.reload();
                    }
                }
           }
        });
    }
});
   

$('.icon2').on('click',function(){ 
    let id = $(this).attr('data-id');
    $('.delete-operation'+id).toggle();
});

});

</script>
