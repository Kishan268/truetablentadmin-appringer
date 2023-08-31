@if ($company->trashed())
    <span class="badge badge-success">@lang('labels.general.yes')</span>
@else
    <a href="{{ route('admin.auth.user.confirm', $company) }}" data-toggle="tooltip" data-placement="top" title="Block" name="confirm_item">
        <span class="badge badge-danger" style="cursor:pointer">@lang('labels.general.no')</span>
    </a>
@endif
