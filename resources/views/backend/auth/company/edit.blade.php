@extends('backend.layouts.app')

@section('title', __('labels.backend.access.company.management') . ' | ' . __('Edit'))

@section('content')
    {{-- {{ html()->form('POST', route('admin.auth.company.update',$company->id))->class('form-horizontal')->open() }} --}}
    <form class="form-horizontal" action="{{ route('admin.auth.company.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            @lang('labels.backend.access.company.management')
                            <small class="text-muted">Edit Company</small>
                        </h4>
                    </div><!--col-->
                </div><!--row-->

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">

                        <div class="form-group row mt-2">
                            <label class="col-md-2 form-control-label" for="company_name">Company Name</label>

                            <input type="hidden" name="id" value="{{ $company->id}}">
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="company_name" id="company_name" placeholder="Company Name" maxlength="191" required="" autofocus="" value="{{ $company->name ? $company->name  : old('company_name')}}">
                                @if ($errors->has('company_name'))
                                    <div class="error text-danger">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div><!--col-->
                        </div>

                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="website">Website</label>

                            <div class="col-md-10">
                                <input class="form-control" type="website" name="website" id="website" placeholder="Website" maxlength="191" required="" autofocus="" value="{{ $company->website ? $company->website  : old('website')}}">
                                @if ($errors->has('website'))
                                    <div class="error text-danger">{{ $errors->first('website') }}</div>
                                @endif
                            </div><!--col-->
                        </div>

                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="location">Location</label>

                            <div class="col-md-10">
                                <select class="form-control select2" name="location" id="location">
                                    <option value="">Select Location</option>
                                    @if(count($data['location']) > 0)
                                        @foreach($data['location'] AS $location)
                                            <option value="{{ $location->id }}" {{ $location->id  === $company->location_id ? 'selected' :''}}>{{ $location->name.', '.$location->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div><!--col-->
                        </div>

                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="company_size">Company Size</label>

                            <div class="col-md-10">
                                <select class="form-control select2" name="company_size" id="company_size">
                                    @if(count($data['company_sizes']) > 0)
                                        @foreach($data['company_sizes'] AS $company_size)
                                            <option value="{{ $company_size->id }}" {{$company_size->id === $company->size_id ? 'selected' :''}}>{{ $company_size->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div><!--col-->
                        </div>

                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="industry_domain">Industry Domain</label>

                            <div class="col-md-10">
                                <select class="form-control select2" name="industry_domain" id="industry_domain">
                                    @if(count($data['industry_domains']) > 0)
                                        @foreach($data['industry_domains'] AS $industry_domain)
                                            <option value="{{ $industry_domain->id }}" {{$industry_domain->name === $company->industry_domain_name ? 'selected' :''}}>{{ $industry_domain->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div><!--col-->
                        </div> 
                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="industry_domain">Logo</label>

                            <div class="col-md-10">
                                 <input class="form-control" type="file" name="logo" id="logo" placeholder="Website" maxlength="191"  autofocus="" value="">
                                @if($company->logo)
                                    <a href="{{ $company->logo ? $company->logo  : ''}}"><img src="{{ $company->logo ? $company->logo  : ''}}" width="auto" height="50px"></a>
                                @endif

                            </div><!--col-->
                        </div>
                        <div class="form-group row  mt-2">
                            <label class="col-md-2 form-control-label" for="industry_domain">Cover Image</label>
                            <div class="col-md-10">
                                 <input class="form-control" type="file" name="cover_pic" id="cover_pic" placeholder="Website" maxlength="191"  autofocus="" value="">
                                 @if($company->cover_pic)
                                    <a href=" {{ $company->cover_pic ?  $company->cover_pic : '' }}"><img src="{{ $company->cover_pic ?  App\Helpers\SiteHelper::getObjectUrl($company->cover_pic) : '' }}" width="auto" height="50px"></a>
                                 @endif

                            </div><!--col-->
                        </div>

                    </div><!--col-->
                </div><!--row-->
            </div><!--card-body-->

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.auth.allcompany.index'), __('buttons.general.cancel')) }}
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
        $('.select2-nosearch').select2({
          minimumResultsForSearch: 20,
        }); 

        $(document).on('focus', '.select2.select2-container', function (e) {
          var isOriginalEvent = e.originalEvent // don't re-open on closing focus event
          var isSingleSelect = $(this).find(".select2-selection--single").length > 0 // multi-select will pass focus to input
          $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
          });
          if (isOriginalEvent && isSingleSelect) {
            $(this).siblings('select:enabled').select2('open');
          } 

        });
        
    });
</script>
