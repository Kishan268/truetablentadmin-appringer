@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.create'))


@section('content')

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        {{ html()->form('POST', route('admin.auth.user.store'))->class('form-horizontal')->open() }}
          <div class="row mb-3">
                @if($type == 'company admin' || $type == 'company user')
                <div class="col-md-6">
                  <label class="form-label">Company</label>
                  <select class="form-control select2" name="company_id" id="company_id">
                        @if(count($companies) > 0)
                            <option value="NULL">Select Company</option>
                            @foreach($companies AS $company)
                                <option selected="{{ isset($company_id) && $company_id == $company->id ? 'checked' : '' }}" value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @else
                    <input type="hidden" name="company_id" value="NULL">
                @endif
                <div class="col-md-6">
                 {{ html()->label(__('validation.attributes.backend.access.users.first_name'))->class('col-md-6 form-label')->for('first_name') }}
                  {{ html()->text('first_name')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.backend.access.users.first_name'))
                        ->attribute('maxlength', 191)
                        ->required()
                        ->autofocus() }}
                @if($errors->has('first_name'))
                  <div class="error text-danger">{{ $errors->first('first_name') }}</div>
                @endif
                </div>
          
                <div class="col-md-6 mt-2">
                 {{ html()->label(__('validation.attributes.backend.access.users.last_name'))->class('col-md-6 form-label')->for('last_name') }}
                  {{ html()->text('last_name')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.backend.access.users.last_name'))
                        ->attribute('maxlength', 191)
                        ->required() }}
                @if($errors->has('last_name'))
                  <div class="error text-danger">{{ $errors->first('last_name') }}</div>
                @endif
                </div>
                <div class="col-md-6 mt-2">
                  {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-6 form-label')->for('email') }}
                  {{ html()->email('email')
                        ->class('form-control')
                        ->placeholder(__('validation.attributes.backend.access.users.email'))
                        ->attribute('maxlength', 191)
                        ->required() }}
                @if($errors->has('email'))
                  <div class="error text-danger">{{ $errors->first('email') }}</div>
                @endif
                </div>
          
                <div class="col-md-6 mt-2">
                  {{ html()->label(__('validation.attributes.backend.access.users.password'))->class('col-md-6 form-label')->for('password') }}
                  {{ html()->password('password')
                            ->class('form-control')
                            ->placeholder(__('validation.attributes.backend.access.users.password'))
                            ->required() }}
                @if($errors->has('password'))
                  <div class="error text-danger">{{ $errors->first('password') }}</div>
                @endif
                </div>
                <div class="col-md-6 mt-2">
                  {{ html()->label(__('validation.attributes.backend.access.users.password_confirmation'))->class('col-md-6 form-label')->for('password_confirmation') }}
                   {{ html()->password('password_confirmation')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.users.password_confirmation'))
                                ->required() }}
                </div>
          
                <div class="col-md-6 mt-2">
                        {{ html()->label(__('validation.attributes.backend.access.users.active'))->class('col-md-6 form-label')->for('active') }}
                        <div class="form-check form-switch mb-2">
                            {{ html()->checkbox('active', true)->class('form-control form-check-input') }} 
                            <span class="switch-slider" data-checked="yes" data-unchecked="no"></span>
                       </div>

                    {{--  <label class="switch switch-label switch-pill switch-primary">
                        {{ html()->checkbox('active', true)->class('form-control') }}
                        <span class="switch-slider" data-checked="yes" data-unchecked="no"></span>
                    </label> --}}
                </div>
                <div class="col-md-6 mt-2">
                    {{ html()->label(__('validation.attributes.backend.access.users.confirmed'))->class('col-md-6 form-label')->for('confirmed') }}
                    <div class="form-check form-switch mb-2">
                        {{ html()->checkbox('confirmed', true)->class('form-control form-check-input') }} 
                        <span class="switch-slider" data-checked="yes" data-unchecked="no"></span>
                   </div>

                  {{-- {{ html()->label(__('validation.attributes.backend.access.users.confirmed'))->class('col-md-2 form-label')->for('confirmed') }}
                    <label class="switch switch-label switch-pill switch-primary">
                        {{ html()->checkbox('confirmed', true)->class('form-control') }}
                        <span class="switch-slider" data-checked="yes" data-unchecked="no"></span>
                    </label> --}}
                </div>

          @if(! config('access.users.requires_approval'))
                <div class="col-md-6 mt-2">
                    {{ html()->label(__('validation.attributes.backend.access.users.send_confirmation_email') . '<br/>' . '<small>' .  __('strings.backend.access.users.if_confirmed_off') . '</small>')->class('col-md-6 form-control-label')->for('confirmation_email') }}
                    <div class="form-check form-switch mb-2 ">
                        {{ html()->checkbox('confirmation_email')->class('switch-input form-check-input') }}
                        <span class="switch-slider" data-checked="yes" data-unchecked="no"></span>
                    </div>
                </div><!--col-->
          </div>

        @endif
        @if($type != '')
        <input type="hidden" name="roles[]" value="{{ $type }}">
        @else
            <div class="form-group row">
                {{ html()->label(__('labels.backend.access.users.table.abilities'))->class('col-md-2 form-control-label') }}
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>@lang('labels.backend.access.users.table.roles')</th>
                                <th>@lang('labels.backend.access.users.table.permissions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    @if($roles->count())
                                        @foreach($roles as $role)
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="checkbox d-flex align-items-center">
                                                        {{-- {{ html()->label(
                                                                html()->checkbox('roles[]', old('roles') && in_array($role->name, old('roles')) ? true : false, $role->name)
                                                                      ->class('form-control form-check-input')
                                                                      ->id('role-'.$role->id))
                                                            ->class('switch switch-label switch-pill switch-primary mr-2')
                                                            ->for('role-'.$role->id) }} --}}
                                                            <div class="form-check form-switch ">

                                                                    {{html()->checkbox('roles[]', old('roles') && in_array($role->name, old('roles')) ? true : false, $role->name)
                                                                      ->class('form-check-input')
                                                                      ->id('role-'.$role->id)}}
                                                            </div>
                                                        {{ html()->label(ucwords($role->name))->for('role-'.$role->id) }}
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @if($role->id != 1)
                                                        @if($role->permissions->count())
                                                            @foreach($role->permissions as $permission)
                                                                <i class="fas fa-dot-circle"></i> {{ ucwords($permission->name) }}
                                                            @endforeach
                                                        @else
                                                            @lang('labels.general.none')
                                                        @endif
                                                    @else
                                                        @lang('labels.backend.access.users.all_permissions')
                                                    @endif
                                                </div>
                                            </div><!--card-->
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($permissions->count())
                                        @foreach($permissions as $permission)
                                            <div class="checkbox d-flex align-items-center">
                                               {{--  {{ html()->label(
                                                        html()->checkbox('permissions[]', old('permissions') && in_array($permission->name, old('permissions')) ? true : false, $permission->name)
                                                              ->class('switch-input')
                                                              ->id('permission-'.$permission->id)
                                                            . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                                        ->class('switch switch-label switch-pill switch-primary mr-2')
                                                    ->for('permission-'.$permission->id) }} --}}
                                                            <div class="form-check form-switch ">
                                                                {{ html()->checkbox('permissions[]', old('permissions') && in_array($permission->name, old('permissions')) ? true : false, $permission->name)
                                                              ->class('form-check-input')
                                                              ->id('permission-'.$permission->id)}}
                                                            </div>
                                                {{ html()->label(ucwords($permission->name))->for('permission-'.$permission->id) }}
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!--col-->
            </div><!--form-group-->
        @endif
        <button class="btn btn-primary mt-2" type="submit">Save</button>
        {{ html()->form()->close() }}
      </div>
    </div>
  </div>
</div>

@endsection
@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
@endpush