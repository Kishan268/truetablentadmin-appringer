@if ($user->trashed())
    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
        <a href="{{ route('admin.auth.user.restore', [$user,'type' => $type]) }}" name="confirm_item" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="@lang('buttons.backend.access.users.restore_user')">
         <i class='fa fa-refresh'></i>

        </a>&nbsp;
        @can('delete_user')
        <a href="{{ route('admin.auth.user.delete-permanently', [$user,'type' => $type]) }}" name="confirm_item" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="@lang('buttons.backend.access.users.delete_permanently')">
            <i class="fa fa-trash"></i>
        </a>
        @endcan
    </div>
@else
    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
        <a href="{{ route('admin.auth.user.show', [$user,'type' => $type]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-primary btn-xs">
            <i class="fa fa-eye"></i>
        </a>&nbsp;
        
        @if($type != "administrator")
        <div class="btn-group btn-group-sm" role="group">
           <button id="userActions" type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('labels.general.more')
            </button> 
            <div class="dropdown-menu" aria-labelledby="userActions">
                @can('update_user')
                    <a href="{{ route('admin.auth.user.edit', $user) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="dropdown-item btn-xs">Edit
                        <i class="fa fa-edit"></i>
                    </a>
                    @endcan
                    @if($user->hasRole('candidate'))
                        <a href="{{ route('admin.auth.user.rate', $user) }}" data-toggle="tooltip" data-placement="top" title="Rate & Evaluate" class="dropdown-item btn-xs">Rate & Evaluate
                            <i class="fa fa-star-half-o"></i>
                        </a>
                @endif
                 @if ($user->id !== auth()->id())
                    <a href="{{ route('admin.auth.user.clear-session', $user) }}"
                       data-trans-button-cancel="@lang('buttons.general.cancel')"
                       data-trans-button-confirm="@lang('buttons.general.continue')"
                       data-trans-title="@lang('strings.backend.general.are_you_sure')"
                       class="dropdown-item" name="confirm_item">@lang('buttons.backend.access.users.clear_session')</a>
                @endif


                @if (! $user->isConfirmed() && ! config('access.users.requires_approval'))
                    <a href="{{ route('admin.auth.user.account.confirm.resend', $user) }}" class="dropdown-item btn-xs">@lang('buttons.backend.access.users.resend_email')</a>
                @endif 

                @if ($user->id !== 1 && $user->id !== auth()->id())
                     @switch($user->active)
                        @case(0)
                            <a href="{{ route('admin.auth.user.mark', [$user, 1,]) }}" class="dropdown-item">@lang('buttons.backend.access.users.activate')</a>
                        @break

                        @case(1)
                            <a href="{{ route('admin.auth.user.destroy', $user) }}"
                               data-method="delete"
                               data-trans-button-cancel="@lang('buttons.general.cancel')"
                               data-trans-button-confirm="@lang('buttons.general.crud.delete')"
                               data-trans-title="@lang('strings.backend.general.are_you_sure')"
                               class="dropdown-item btn-xs">@lang('buttons.backend.access.users.deactivate')</a>
                        @break
                    @endswitch
                   
                @endif
            </div>
        </div>
        @else
             <div class="btn-group btn-group-sm" role="group">
               <button id="userActions" type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('labels.general.more')
                </button> 
                <div class="dropdown-menu" aria-labelledby="userActions">
                    <a href="{{ route('admin.auth.user.change-password', $user) }}" class="dropdown-item btn-xs">@lang('buttons.backend.access.users.change_password')</a>
                </div>
            </div>
        @endif
    </div>
@endif