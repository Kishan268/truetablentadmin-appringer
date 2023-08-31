@push('after-styles')
<style type="text/css">
    .select2-selection--single{
        min-height: 32px!important;
    }
</style>
@endpush
{{ html()->modelForm($logged_in_user, 'POST', route('frontend.user.preferences.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
    @method('PATCH')

    @php $details = $logged_in_user->details; @endphp
    <h4 class="mt-3">Job Preferences</h4>
    <hr/>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for='preferedJobType'>Preferred Job-type</label><br/>
                <select class="form-control" name="job_type[]" multiple="multiple" id='job_type' width="100%">
                    <option value="full_time" {{in_array('full_time', $details['job_type']) ? 'selected' : ''}}>Full Time</option>
                    <option value="part_time" {{in_array('part_time', $details['job_type']) ? 'selected' : ''}}>Part Time</option>
                    <option value="corp_to_corp" {{in_array('corp_to_corp', $details['job_type']) ? 'selected' : ''}}>Corp-to-Corp</option>
                </select>
            </div><!--form-group-->
        </div><!--col-->
    {{-- </div>row --}}

    {{-- <div class="row"> --}}
        <div class="col">
            <div class="form-group">
                {{ html()->label('Preferred Location')->for('preferred_location') }}<br/>
                <select class="form-control" name="preferred_location" id='locations' width="100%">
                    @if($logged_in_user->preferred_location != null)
                    <option value="{{$logged_in_user->preferred_location['id']}}">{{$logged_in_user->preferred_location['text']}}</option>
                    @endif
                </select>
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group">
                {{ html()->label('Minimum expected Salary (Annually)')->for('min_salary') }}

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" min="1" class="form-control" maxlength="5" onKeyDown="if(this.value.length > 5 && event.keyCode!=8) return false;" name="min_salary" aria-label="Amount (to the nearest dollar)" value="{{old('min_salary') ? old('min_salary') : $details['min_salary']}}">
                    <div class="input-group-append">
                        <span class="input-group-text">.00</span>
                    </div>
                </div>
                {{-- <span class="text-danger">{{$errors->first('min_salary')}}</span> --}}
            </div><!--form-group-->
        </div><!--col-->
        <div class="col">
            <div class="form-group">
                <label for="telecommute">Telecommute Jobs Only</label><br/>
                <input type="checkbox" id="toggle" class="checkbox" name="telecommute" {{$details['telecommute'] ? 'checked' : ''}} />
                <label for="toggle" class="switch"></label>
            </div>
        </div>
    </div><!--row-->

    <h4 class="mt-4">Notification Preferences</h4>
    <hr/>
    <div class="row">
        <div class="col">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="notification_new_jobs" id="notification_new_jobs" {{$details['notification_new_jobs'] ? 'checked' : ''}}>
                <label class="custom-control-label" for="notification_new_jobs">Get emails for new matching jobs posted</label>
            </div>
        </div>
        <div class="col">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="notification_profile_viewed" id="notification_profile_viewed" {{$details['notification_profile_viewed'] ? 'checked' : ''}}>
                <label class="custom-control-label" for="notification_profile_viewed">Get emails when recruiters view my WorkProfile</label>
            </div>
        </div>
    </div>

    <hr/>
    <div class="row">
        <div class="col text-center">
            <div class="form-group mb-0 clearfix">
                <button class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                {{-- {{ form_submit(__('labels.general.buttons.save')) }} --}}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
{{ html()->closeModelForm() }}

@push('after-scripts')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': self.csrf
                }
            });
            $('#job_type').select2({width: '100%', multiple: true});
            $('select#searchCompany').select2({
                placeholder: 'Start typing a company name...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: false,
                selectOnClose: true,
                scrollAfterSelect: true,
                tags: true,
                width: '40%',
                ajax: {
                    url: "/getSkillList",
                    type: "post",
                    dataType: 'json'
                }
            });

            $('select#locations').select2({
                placeholder: 'Try typing a city, state or zip...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: true,
                selectOnClose: true,
                scrollAfterSelect: true,
                multiple: false,
                width: '100%',
                // tags: true,
                ajax: {
                    url: "{{ route('frontend.getLocations') }}",
                    type: "post",
                    dataType: 'json'
                }
            });
        });
    </script>
@endpush
