{{ html()->modelForm($logged_in_user, 'POST', route('frontend.user.company-profile.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
@method('PATCH')

@php $details = $logged_in_user->companyDetails;@endphp
<h4 class="mt-3">Logo</h4>
<hr />
<div class="row">
    <div class="col">
        <div class="form-inline row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <img src="{{ $details->logo == null ? asset('img/frontend/logo.png') : '/getImage/' . $details->logo . '/logos' }}"
                    class="rounded mx-auto d-block img-thumbnail companyLogo w-50" alt="{{ $details['name'] }} Logo">
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 custom-file">
                <input type="file" class="custom-file-input" name="logo" id="logo"
                    accept=".png,.jpeg,.jpg,.bmp">
                <label class="custom-file-label" for="customFile">Choose Logo Image</label>
            </div>
        </div>
    </div>
</div>

<h4 class="mt-5">Description</h4>
<hr />
<div class="row">
    <div class="col">
        <div class="form-group">
            <textarea class="form-control" rows="5" name="description" maxlength="500"
                placeholder="Enter brief description about your company. This will appear under job details.">{{ $details->description }}</textarea>
        </div>
    </div>
</div>

<h4 class="mt-5">Social</h4>
<hr />
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
            <label for="LinkedIn"></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-facebook-square"></i></span>
                </div>
                <input type="text" class="form-control" name="facebook" aria-label="Facebook Page URL"
                    value="{{ old('facebook') ? old('facebook') : $details['facebook'] }}">
            </div>
            <span class="text-danger">{{ $errors->first('facebook') }}</span>
        </div>
        <!--form-group-->
    </div>
    <!--col-->
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
            <label for="LinkedIn"></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-twitter-square"></i></span>
                </div>
                <input type="text" class="form-control" name="twitter" aria-label="Twitter Profile URL"
                    value="{{ old('twitter') ? old('twitter') : $details['twitter'] }}">
            </div>
            <span class="text-danger">{{ $errors->first('twitter') }}</span>
        </div>
        <!--form-group-->
    </div>
    <!--col-->
</div>
<!--row-->

<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
            <label for="LinkedIn"></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                </div>
                <input type="text" class="form-control" name="linkedin" aria-label="LinkedIn Profile URL"
                    value="{{ old('linkedin') ? old('linkedin') : $details['linkedin'] }}">
            </div>
            <span class="text-danger">{{ $errors->first('linkedin') }}</span>
        </div>
        <!--form-group-->
    </div>
    <!--col-->
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
            <label for="equal-opportunity-employer">Is the Company an <strong>"Equal Opportunity Employer"</strong>
                ?</label><br />
            <input type="checkbox" id="toggle" class="checkbox" name="equal_opportunity_employer"
                {{ $details['equal_opportunity_employer'] ? 'checked' : '' }} />
            <label for="toggle" class="switch"></label>
        </div>
    </div>
</div>
<!--row-->

<hr />
<div class="row">
    <div class="col text-center">
        <div class="form-group mb-0 clearfix">
            <button class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            {{-- {{ form_submit(__('labels.general.buttons.save')) }} --}}
        </div>
        <!--form-group-->
    </div>
    <!--col-->
</div>
<!--row-->
{{ html()->closeModelForm() }}

@push('after-scripts')
    <script type="text/javascript">
        $(function() {
            $('input#logo').on('change', function(e) {
                var fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').text(fileName);
            });
            // Load image preview before upload
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.companyLogo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }
            $("#logo").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
