@push('after-styles')
<style type="text/css">
    .switch { 
        position : relative ;
        display : inline-block;
        width : 60px;
        height : 30px;
        background-color: #eee;
        border-radius: 40px;
    }
    .switch::after {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        top: 2px;
        left: 1px;
        transition: all 0.4s;
    }
    .checkbox:checked + .switch::after {
        left : 30px; 
    }
    .checkbox:checked + .switch {
        background-color: #7983ff;
    }
    .checkbox { 
        display : none;
    }
    .select2-container--default .select2-search--inline .select2-search__field{
        width: 150%!important;
    }
    /* .findJobs{ */
        /* max-width: 10%!important; */
    /* } */
    /* } */
    .advSearchDiv{
        max-width: 20%!important;
    }
    @media (max-width: 576px), (max-width: 768px){
        .findJobs, .advSearchDiv{
            max-width: 100%!important;
        }
    }
    /* .select2-container--default .select2-selection--multiple{
        padding: 1%;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: #1e1e1e;
    } */
</style>
@endpush
{{-- <div class=""> --}}
    {{-- <h3>@lang('strings.frontend.search', ['type' => 'Jobs'])</h3> --}}
    <form id="searchForm" method="post" action="{{ route('frontend.searchCandidates') }}">
    @csrf
    <div class="row">
        <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center findJobs">
            <h5 class="display-4 font-weight-bold" style="font-size: 1.5rem;">Find Job Seekers</h5>
        </div>
        <div class="seekerSearchDiv animated col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group">
                <input class="form-control" placeholder="Search Job Seekers" value="{{array_key_exists('work_profiles_summary', $params) ? $params['work_profiles_summary'] : ''}}"  name="work_profiles.summary" />
            </div>
        </div>
        {{-- <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 row"> --}}
    {{-- </div><!--row--> --}}

    {{-- <div class="row justify-content-center my-3"> --}}
        <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center btnp animated fadeInUp">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            {{-- <button class="btn btn-warning" type="button" id="resetSearch"><i class="fas fa-undo"></i> Reset</button> --}}
        </div>
        <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center advSearchDiv">
            <button class="btn btn-outline-dark advSearchBtn" type="button" data-toggle="collapse"
                data-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch" title="Click for options">
                Advanced
                <span class="d-none arrowUp"><i class='fas fa-angle-up'></i></span>
                <span class="arrowDown"><i class='fas fa-angle-down'></i></span>
            </button>
        </div>
    </div>

        {{-- <div class="col-2 text-center">
            <button class="btn btn-primary mr-1" type="submit"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-warning" type="button" id="resetSearch"><i class="fas fa-undo"></i> Reset</button>
        </div>
        <div class="col-2">
            <button class="btn btn-outline-default" type="button" data-toggle="collapse" data-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch" title="Click for options">Advanced Search</button>
        </div> --}}
    {{-- </div> --}}
    <div class="collapse" id="advancedSearch">
        <div class="card card-body mx-n4" style="background-color: rgba(200,200,200,0.3);">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="form-group">
                        <label for="seekerSkills">Skills</label>
                        <select class="form-control select2" name="skills[]" multiple="multiple" id="skills"
                            placeholder="Try typing a Skill(s)" width="100%">
                            @if(array_key_exists('skills', $params))
                                @foreach ($params['skills'] as $skill)
                                <option value="{{ $skill }}" selected="selected">{{ $skill }}</option>
                                @endforeach
                            @endif
                        </select>
                        {{-- </div> --}}
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="form-group">
                        <label for="seekerLocations">Locations</label>
                        <select class="form-control" name="locations[]" id="locations"
                            multiple="multiple" width="100%">
                            @if(isset($locations) && count($locations) > 0 && array_key_exists('locations', $params))
                                @foreach ($locations as $loc)
                                <option value="{{$loc['id']}}" selected="selected">{{$loc['text']}}</option>    
                                @endforeach
                            @elseif(!auth()->guest() && auth()->user()->pref_location != null && count($params) == 0)
                            <option selected="selected" value="{{auth()->user()->pref_location['id']}}">{{auth()->user()->pref_location['text']}}
                            </option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-3 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="experience">Experience(in Years)</label>
                        <div class="row ml-1" style='width:98%;'>
                            <input type="number" min="0" max="100"
                            value="{{array_key_exists('min-experience', $params) ? $params['min-experience'] : ''}}"
                            class="form-control col mr-1" name="min-experience" placeholder="Min.">
                            <input type="number" min="0" max="100"
                            value="{{array_key_exists('max-experience', $params) ? $params['max-experience'] : ''}}"
                            class="form-control col" name="max-experience" placeholder="Max">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="form-group">
                        <label for="work-authorization">Work Authorization</label>
                        <select class="form-control" id='work_authorization' name="work_profiles.work_authorization">
                            <option value='citizen'>Citizen</option>
                            <option value='GC'>GC</option>
                            <option value='H1B'>H1B</option>
                            <option value='H4EAD'>H4 EAD</option>
                            <option value='L2EAD'>L2 EAD</option>
                            <option value='TN_Visa'>TN Visa</option>
                            <option value='F1_Opt'>F1 OPT(STEM)</option>
                        </select>
                    </div>
                </div>

                
                <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-2">
                    <div class="form-group">
                        <label for="salary">Expected Salary($)</label>
                        <div class="row ml-1" style="width: 98%;">
                            <div class="input-group mb-3 col" style="padding: 0;">
                                <input type="number" min="0" value="{{array_key_exists('min-salary', $params) ? $params['min-salary'] : ''}}" class="form-control" name="user_details.min_salary" placeholder="Salary starting from...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">K</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='mobility'>Mobility Readiness</label><br />
                        <input type="checkbox" id="mobility" name="user_details.telecommute" class="checkbox"
                            {{array_key_exists('user_details_telecommute', $params) && $params['user_details_telecommute'] == 'on' ? 'checked' : ''}} />
                        <label for="mobility" class="switch"></label>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
                    <div class="form-group">
                        <label for='job-type'>Job Type</label>
                        <select class="form-control" name="type">
                            <option value="">Select Job Type</option>
                            <option value='full-time'>Full Time</option>
                            <option value='part-time'>Part Time</option>
                            <option value='contract'>Contract</option>
                            <option value='C2C'>C2C</option>
                            <option value='internship'>Intership</option>
                            <option value='temp'>Temp</option>
                            <option value='others'>Others</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for="posted-after">Date of WP Update</label>
                        <div class="input-group updated_atGrp">
                            {{-- <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)"> --}}
                            <input type="date" name="updated_at" placeholder="Click to pick Date" class="form-control" data-input>
                            <div class="input-group-append">
                                <span class="input-group-text input-button" data-toggle><i class="fas fa-calendar"></i></span>
                                {{-- <span class="input-group-text">0.00</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <button class="btn btn-primary mr-2 float-right" type="submit"><i class="fas fa-search"></i> Search</button>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <button class="btn btn-warning float-left" type="button" id="resetSearch"><i class="fas fa-undo"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>
    </form>
{{-- </div><!--row--> --}}
@push('after-scripts')
    <script type="text/javascript">
        $(function(){
            $('.updated_atGrp').flatpickr({wrap: true, maxDate: 'today'});
            $('#resetSearch').on('click', function(){
                document.getElementById('searchForm').reset();
                var inputs = $('#searchForm').find('input');
                $.each(inputs, function(i, v){
                    if($(v).attr('name') == '_token') return false;
                    $(v).val('').removeAttr('checked');
                });
                toastr.info('All Search parameters have been reset!');
            });

            $('.advSearchBtn').on('click', function(){
                $('.arrowUp').toggleClass('d-none');
                $('.arrowDown').toggleClass('d-none');
            });
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('select#skills').select2({
                placeholder: 'Try typing a Skill(s)...',
                minimumInputLength: 1,
                allowClear: true,
                selectOnClose: true,
                closeOnSelect: false,
                scrollAfterSelect: true,
                multiple: true,
                width: '100%',
                tags: true,
                ajax: {
                    url: "{{ route('frontend.getSkills') }}",
                    type: "post",
                    dataType: 'json'
                }
            });
            
            $('select#locations').select2({
                placeholder: 'Try typing a city, state or zip...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: false,
                selectOnClose: true,
                scrollAfterSelect: true,
                multiple: true,
                width: '100%',
                // tags: true,
                ajax: {
                    url: "{{ route('frontend.getLocations') }}",
                    type: "post",
                    dataType: 'json'
                }
            });

            $('select#work_authorization').select2({
                placeholder: 'Select Work Authorization',
                multiple: true,
                width: '100%'
            }).val(null).trigger('change');

            $('#advancedSearch').on('show.bs.collapse hidden.bs.collapse', function() {
                $('.btnp').toggleClass('d-none');
            });

            let minExp = $('input[name=min-experience]').val(); let maxExp = $('input[name=max-experience]').val();
            function getExp(){
                minExp = parseInt($('input[name=min-experience]').val());
                maxExp = parseInt($('input[name=max-experience]').val());
            }

            $('input[name=min-experience]').on('change', function(){
                getExp();
                if(minExp > maxExp && maxExp != NaN){ $('input[name=min-experience]').val(maxExp); }
            });
            $('input[name=max-experience]').on('change', function(){
                getExp();
                if(minExp > maxExp && minExp != NaN){ $('input[name=max-experience]').val(minExp); }
            });
        });
    </script>
@endpush