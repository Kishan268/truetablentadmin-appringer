@if ($user->hasVerifiedEmail())
    @if ($user->id !== 1 && $user->id !== auth()->id())
        <a href="{{ route( 'admin.auth.user.unconfirm', [$user,'type' => $type]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.backend.access.users.unconfirm')" name="confirm_item">
            <span class="text-success" style="cursor:pointer">@lang('labels.general.yes')</span>
        </a>
    @else
        <span class="text-success">@lang('labels.general.yes')</span>
    @endif
@else
    <a href="{{ route('admin.auth.user.confirm', [$user,'type' => $type]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.backend.access.users.confirm')" name="confirm_item">
        <span class="text-danger" style="cursor:pointer">@lang('labels.general.no')</span>
    </a>
@endif
