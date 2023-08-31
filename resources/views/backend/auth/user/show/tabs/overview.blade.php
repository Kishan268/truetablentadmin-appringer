<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                <td><img src="{{ $user->picture }}" class="user-profile-image" /></td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $user->email }}</td>
            </tr>

            

           {{--  <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                <td>{{ $user->timezone }}</td>
            </tr> --}}
             @if($user->last_login_at)
                <tr>
                    <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_at')</th>
                    <td>
                       
                            {{ timezone()->convertToLocal($user->last_login_at) }}
                        
                    </td>
                </tr>
             @endif
             @if($user->last_login_ip)
                <tr>
                    <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                    <td>{{ $user->last_login_ip}}</td>
                </tr>
            @endif
        </table>
    </div>
</div><!--table-responsive-->
