<style type="text/css">
    .error {
        color: red;
    }

    .table-responsive {
        overflow-x: clip !important;
    }

    .select2 {
        width: 100% !important;
    }
</style>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $selecteRole = ['administrator', 'candidate', 'company admin', 'company user', 'evaluator'];
                $count = 1;
            @endphp
            @if (isset($roles->users))
                @foreach ($roles->users as $user)
                    <tr id="user-1">
                        <td>{{ $count++ }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ ucWords($roles->name) }}</td>
                        <td>
                            <span class="text-danger"> <a href="javascript:;" data-role-member-id="{{ $user->id }}"
                                    class="btn btn-sm btn-danger btn-rounded delete-role-member">Remove</a></span>
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>
    <hr />
    <form method="POST" action="{{ route('admin.auth.permission.storeRole') }}" accept-charset="UTF-8"
        id="createProjectCategory" class="ajax-form">
        @method('POST')
        @csrf
        <div class="form-body">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="form-group">
                        <label>Choose Members</label>
                        <select class="select2 select2-multiple " multiple="multiple" data-placeholder="Choose Members"
                            name="user_id[]" id="multiple_user">
                            @if (isset($allUsers->users))
                                @foreach ($allUsers->users as $allUser)
                                    <option value="{{ $allUser->id }}">
                                        {{ ucwords($allUser->first_name) . ' [' . $allUser->email . ']' }}{{--  @if ($allUser->id == $allUser->id)
                                           (YOU) @endif --}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div id="first_name"></div>
                        <span class="text-danger d-none invalidAmount">Please enter role</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions mt-2">
            <button type="button" id="save-users" class="btn btn-success pull-right"> <i class="fa fa-check"></i>
                Save</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $("#multiple_user").select2({
        dropdownParent: "#projectCategoryModal"
    });


    $(document).ready(function() {

        $('#save-users').click(function() {
            var user_id = $('.select2-multiple').val();
            var role_id = {{ $roles->id }};
            console.log(user_id)
            if (user_id === '') {
                // $('#first_name').after('<span class="error">This field is required</span>'); 
                SwalMessage('This field is required!', 'warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.auth.permission.assignRole') }}',
                    type: "POST",
                    data: {
                        'user_id': user_id,
                        'role_id': role_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        SwalMessage('Role assigned successfully!', 'success');
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                    },
                    error: function(errors) {
                        SwalMessage('Somthing went wrong!', 'warning');
                    }
                })
            }
        })
        $('.delete-role-member').click(function() {
            var roleMemberId = $(this).attr('data-role-member-id');
            $.ajax({
                url: '{{ route('admin.auth.permission.deleteRoleMembers') }}',
                type: "POST",
                data: {
                    'roleMemberId': roleMemberId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    SwalMessage('Memer deleted successfully!', 'success');
                    setTimeout(function() {
                        location.reload(true);
                    }, 2000);
                }

            })

        });
        $('#name').keypress(function() {
            $(".error").css("display", "hide");
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
