@extends('backend.layouts.app')

@section('title', __('System Config Management') . ' | ' . __('Edit'))

@section('content')
    {{-- {{ html()->form('POST', route('admin.auth.company.update',$company->id))->class('form-horizontal')->open() }} --}}
    <form class="form-horizontal" action="{{ route('admin.system-config.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            System Config Management
                            <small class="text-muted">Edit System Config</small>
                        </h4>
                    </div><!--col-->
                </div><!--row-->

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">

                        <div class="form-group row mt-2">
                            <label class="col-md-1 form-control-label" for="key">Key</label>
                            <input type="hidden" name="id" value="{{ $systemConfig->id}}">
                            <div class="col-md-11">
                                <input class="form-control" type="text" name="key" id="key" placeholder="Company Name" maxlength="191" required="" autofocus="" value="{{ $systemConfig->key ? $systemConfig->key  : old('key')}}" disabled>
                                @if ($errors->has('key'))
                                    <div class="error text-danger">{{ $errors->first('key') }}</div>
                                @endif
                            </div><!--col-->
                        </div>

                        <div class="form-group row  mt-2">
                            <label class="col-md-1 form-control-label" for="value">Value</label>
                            <div class="col-md-11">
                                <input class="form-control" type="value" name="value" id="value" placeholder="value" maxlength="191" {{$systemConfig->key != 'WHITELISTED_EMAILS' ? 'required' : ''}}  autofocus="" value="{{ $systemConfig->value ? $systemConfig->value  : old('value')}}">
                                @if ($errors->has('value'))
                                    <div class="error text-danger">{{ $errors->first('value') }}</div>
                                @endif
                            </div><!--col-->
                        </div>

                       

                    </div><!--col-->
                </div><!--row-->
            </div><!--card-body-->

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.system-config.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->

                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.update')) }}
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
        </div><!--card-->
    {{-- {{ html()->form()->close() }} --}}
</form>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
