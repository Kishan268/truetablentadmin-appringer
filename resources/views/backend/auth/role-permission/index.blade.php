@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))


@push('after-styles')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">

<style>
    .mytooltip{
        z-index: 999 !important;
    }

    .permission-row {
/*        width: 80%;*/
        background-color: #4f5467;
/*        margin-left: 60px;*/
    }

    .text-white{
        margin-top: 20px;
    }

    .toggle-permission {
        background: #e4e7ea;
        border: 1px solid #e4e7ea;
        border-radius: 3px !important;
        margin-top: 10px;
    }

    .permission-section{
        background-color: #fff;
    }
    .spinner-border {
      color:#14BC9A;
      text-align: center;
      font-family: 'PT Sans Narrow', sans-serif;
      font-size: 25px;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row" >
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row" style="gap: 20px;">
                        <div class="col-md-12">
                            <div class="row bg-title">
                                <div class="col-lg-12 col-md-12 col-xs-12 pull-right">
                                    <button href="javascript:;" id="addRole" class="btn btn-success btn-sm btn-outline  waves-effect waves-light pull-right"><i class="fa fa-gear"></i> Manage Role</button>
                                </div>
                            </div>
                        </div>
                        @forelse($roles as $role)
                            <div class="col-md-12 b-all m-t-10">
                                <div class="row permission-row">

                                    <div class="col-md-4 text-center p-10 bg-inverse ">
                                        <h5 class="text-white"><strong id="role_display_name">{{ ucwords($role->name) }}</strong></h5>
                                    </div>
                                    <div class="col-md-4 text-center bg-inverse role-members " style="padding-top: 14px !important;">
                                         <button class="btn btn-xs btn-danger btn-rounded show-members" data-role-id="{{$role->id}}"><i class="fa fa-users"></i>
                                             {{count($role->users)}} Member(s)</button>
                                    </div>
                                    <div class="col-md-4 p-10 bg-inverse" style="padding-bottom: 8px !important;">
                                        <button class="btn btn-default btn-rounded pull-right toggle-permission" data-role-id="{{ $role->id }}"><i class="fa fa-key"></i> Permissions</button>
                                    </div>
                                    <div class="col-md-12 b-t permission-section" style="display: none;" id="role-permission-{{ $role->id }}" >
                                        <div class="text-center" >
                                          <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                          </div>
                                        </div>
                                        <table class="table ">
                                            <thead>
                                            <tr class="bg-white">
                                                <th>
                                                    <div class="form-group">
                                                        <div class="checkbox checkbox-info form-check form-check-inline  col-md-10">
                                                            <input id="select_all_permission_{{ $role->id }}"
                                                                   @if(count($role->permissions) == $totalPermissions) checked @endif
                                                                   class="select_all_permission form-check-input"  value="{{ $role->id }}" type="checkbox">
                                                            <label for="select_all_permission_{{ $role->id }}" style="margin-top: 2px">Select All</label>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th>View</th>
                                                <th>Add</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                @foreach($modulesData as $key => $moduleData)
                                                    <tr>
                                                        <td>{{ $key }}
                                                        </td>
                                                        @foreach($moduleData as $permission)
                                                            <td>
                                                                <div class="switchery-demo">
                                                                  <input type="checkbox"
                                                                     @if($role->hasPermissionTo($permission->name))
                                                                             checked
                                                                     @endif
                                                                     class="js-switch assign-role-permission permission_{{ $role->id }}" data-size="small" data-color="#00c292" data-permission-id="{{ $permission->id }}" data-role-id="{{ $role->id }}" />
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @empty
                                <div class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:30px"><i
                                            class="ti-lock"></i>
                                            </div>
                                            <div class="title m-b-15">@lang('messages.defaultRolesCantDelete')
                                            </div>
                                            <div class="subtitle">
                                                <a href="javascript:;" id="addRole"
                                                   class="btn btn-success btn-sm btn-outline  waves-effect waves-light"><i
                                                            class="fa fa-gear"></i> @lang("modules.roles.addRole")</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- .row -->
<!-- Modal -->
<div class="modal fade " id="projectCategoryModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body"style="max-height: 800px" >
        
      </div>
    </div>
  </div>
</div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script>
    $('document').ready(function() {
    $('body').find('.spinner-border').hide();


    $('.toggle-permission').click(function () {
        var roleId = $(this).data('role-id');
        $('#role-permission-'+roleId).toggle();
    })


    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });

    // Initialize multiple switches
    var animating = false;
    var masteranimate = false;

    var assignRollPermission = function () {
        var roleId = $(this).data('role-id');
        var permissionId = $(this).data('permission-id');

        if($(this).is(':checked'))
            var assignPermission = 'yes';
        else
            var assignPermission = 'no';

        var url = '{{route('admin.auth.permission.store')}}';

        $.ajax({
            url: url,
            type: "POST",
            data: { 'roleId': roleId, 'permissionId': permissionId, 'assignPermission': assignPermission, '_token': '{{ csrf_token() }}' },
            success: function () {
                $('body').find('.spinner-border').hide();
            }
        })
    };

    $('.assign-role-permission').change(function () {
        $('body').find('.spinner-border').show();
        var roleId = $(this).data('role-id');
        var permissionId = $(this).data('permission-id');

        if($(this).is(':checked'))
            var assignPermission = 'yes';
        else
            var assignPermission = 'no';

        var url = '{{route('admin.auth.permission.store')}}';

        $.ajax({
            url: url,
            type: "POST",
            data: { 'roleId': roleId, 'permissionId': permissionId, 'assignPermission': assignPermission, '_token': '{{ csrf_token() }}' },
            success: function () {
                $('body').find('.spinner-border').hide();
            }
        })
    });

    $('.select_all_permission').change(function () {
        $('body').find('.spinner-border').show();

        if($(this).is(':checked')){
            var roleId = $(this).val();
            var url = '{{ route('admin.auth.permission.assignAllPermission') }}';

            $.ajax({
                url: url,
                type: "POST",
                data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                success: function () {
                    masteranimate = true;
                    if (!animating){
                        var masterStatus = true;
                        $('.assign-role-permission').off('change');
                        $('input.permission_'+roleId).each(function(index){
                            var switchStatus = $('input.permission_'+roleId)[index].checked;
                            if(switchStatus != masterStatus){
                                $(this).trigger('click');
                            }
                        });
                        $('.assign-role-permission').on('change', assignRollPermission);
                        $('body').find('.spinner-border').hide();

                    }
                    masteranimate = false;
                }
            })
        }
        else{
            var roleId = $(this).val();
            var url = '{{ route('admin.auth.permission.removeAllPermission') }}';

            $.ajax({
                url: url,
                type: "POST",
                data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                success: function () {
                    masteranimate = true;
                    if (!animating){
                        var masterStatus = false;
                        $('.assign-role-permission').off('change');
                        $('input.permission_'+roleId).each(function(index){
                            var switchStatus = $('input.permission_'+roleId)[index].checked;
                            if(switchStatus != masterStatus){
                                $(this).trigger('click');
                            }
                        });
                        $('.assign-role-permission').on('change', assignRollPermission);
                        $('body').find('.spinner-border').hide();

                    }
                    masteranimate = false;
                }
            })
        }
    })

    $('.show-members').click(function () {
        var id = $(this).data('role-id');
        $.ajax({
            url: '{{ route('admin.auth.permission.showMembers')}}',
            type: "POST",
            data: { 'id': id, '_token': '{{ csrf_token() }}' },
            success: function (response) {
                $('.modal-title').html('Role Members');
                $("#projectCategoryModal").modal("toggle");
                $(".modal-body").html(response);
            }
        })

        $('#modelHeading').html('Role Members');
        $.ajaxModal('#projectCategoryModal', url);
    })

    $('#addRole').click(function () {
        $.ajax({
            url: '{{ route('admin.auth.permission.create')}}',
            type: "POST",
            data: { 'roleId': 1, '_token': '{{ csrf_token() }}' },
            success: function (response) {
                $('.modal-title').html('Manage Role Members');
                $("#projectCategoryModal").modal("toggle");
                $(".modal-body").html(response);
            }
        })
    });

});


</script>

