<style type="text/css">
    .error {
        color: red;
    }

    .table-responsive {
        overflow-x: clip !important;
    }
</style>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>User Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $selecteRole = ['administrator', 'candidate', 'company admin', 'company user', 'evaluator'];
            @endphp
            @foreach ($roles as $role)
                <tr id="role-1">
                    <td>{{ $role->id }}</td>
                    <td>
                        {{ ucWords($role->name) }}
                    </td>
                    <td>
                        @if (in_array($role->name, $selecteRole))
                            <span class="text-danger">Default role can not be deleted.</span>
                        @else
                            <span class="text-danger"> <a href="javascript:;" data-role-id="{{ $role->id }}"
                                    class="btn btn-sm btn-danger btn-rounded delete-role">Remove</a></span>
                        @endif

                    </td>
                </tr>
            @endforeach
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
                        {{-- <label>Role Name</label>
                        <input type="text" name="name" id="name" class="form-control"> --}}
                        <label>Role Name</label>
                        <input type="text" name="name" id="name" class="form-control" required
                            placeholder="Enter role name" aria-label="roles" aria-describedby="addCashModalBtn1">
                        <div id="first_name"></div>
                        <span class="text-danger d-none invalidAmount">Please enter role</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions mt-2">
            <button type="button" id="save-category" class="btn btn-success pull-right"> <i class="fa fa-check"></i>
                Save</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('#save-category').click(function() {
            var name = $('#name').val();
            if (name === '') {
                // $('#first_name').after('<span class="error">This field is required</span>'); 
                SwalMessage('This field is required!', 'warning');
            } else {
                $.ajax({
                    url: '{{ route('admin.auth.permission.storeRole') }}',
                    type: "POST",
                    data: {
                        'name': name,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        SwalMessage('Role added successfully!', 'success');
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                    },
                    error: function(errors) {
                        SwalMessage('The name has already been taken!', 'warning');
                    }
                })
            }
        })
        $('.delete-role').click(function() {
            var roleId = $(this).attr('data-role-id');
            $.ajax({
                url: '{{ route('admin.auth.permission.deleteRole') }}',
                type: "POST",
                data: {
                    'roleId': roleId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response === 'success') {
                        SwalMessage('Role deleted successfully!', 'success');
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                    } else if (response === 'error') {
                        SwalMessage(
                            'Not able to delete this role, becase this role assigne to many users!',
                            'warning');
                    } else {
                        SwalMessage('Somthing went wrong!', 'warning');
                    }

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
