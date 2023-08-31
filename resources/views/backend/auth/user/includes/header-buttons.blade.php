<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
    <input id="search" type="text" name="keyword" placeholder="Search" value="{{ isset($_REQUEST['q']) ? $_REQUEST['q'] : '' }}">
    <a href="{{ route('admin.auth.user.create',['type'=>$type]) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
    @if($type == 'candidate')
    	<a href="{{ route('admin.auth.user.import') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.bulk_upload')">@lang('labels.general.bulk_upload')</a>
    @endif
</div><!--btn-toolbar-->
