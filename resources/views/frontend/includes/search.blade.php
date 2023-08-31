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
    .findJobs{
        max-width: 10%!important;
    }
    /* } */
    @media (max-width: 576px), (max-width: 768px){
        .findJobs, .advSearchDiv{
            max-width: 100%!important;
        }
    }
    .advSearchDiv{
        max-width: 13%!important;
    }
    @media (min-width: 1200px){
    .col-xl-1 {
        flex: 0 0 10.333333%!important;
        max-width: 10.333333%!important;
    }}
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
    <form id="searchForm" method="post" action="{{ route('frontend.searchJobs') }}">
    @csrf
    <div class="row">
        <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center findJobs">
            <h5 class="display-4 font-weight-bold" style="font-size: 1.5rem;">Find Jobs</h5>
        </div>
        {{-- <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 row"> --}}
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="form-group">
                {{-- <label for="skills" style="font-size: 1.3rem;" class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-form-label text-center">@lang('strings.frontend.skills')</label> --}}
                {{-- <div class="col-10"> --}}
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
                {{-- @php dd(count($params)); @endphp --}}
                {{-- <label for="location" style="font-size: 1rem;" class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 col-form-label">@lang('strings.frontend.location')</label> --}}
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
    {{-- </div><!--row--> --}}

    {{-- <div class="row justify-content-center my-3"> --}}
        <div class="col-6 col-sm-6 col-md-1 col-lg-1 col-xl-1 text-center btnp animated fadeInUp">
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
                <div class="col-12 col-sm-12 col-md-3 col-lg-4 col-xl-3">
                    <div class="form-group">
                        <label for="experience">Experience(in Years)</label>
                        <div class="row ml-1">
                            <input type="number" min="0" max="100" value="{{array_key_exists('min-experience', $params) ? $params['min-experience'] : ''}}" class="form-control col mr-1" name="min-experience" placeholder="Min.">
                            <input type="number" min="0" max="100" value="{{array_key_exists('max-experience', $params) ? $params['max-experience'] : ''}}" class="form-control col" name="max-experience" placeholder="Max">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-2">
                    <div class="form-group">
                        <label for="salary">Salary($)</label>
                        <div class="row ml-1">
                            <div class="input-group mb-3 col" style="padding: 0;">
                                <input type="number" min="0" value="{{array_key_exists('min-salary', $params) ? $params['min-salary'] : ''}}" class="form-control" name="min-salary" placeholder="Min.">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">K</span>
                                </div>
                            </div>
                            {{-- <div class="input-group mb-3 col">
                                <input type="number" min="0" value="{{array_key_exists('max-salary', $params) ? $params['max-salary'] : ''}}" class="form-control" name="max-salary" placeholder="Max">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">000</span>
                                </div>
                            </div> --}}
                        </div>
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

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-2">
                    <div class="form-group">
                        <label for="job-duration">Job Duration</label>
                        <select class="form-control" name="duration">
                            <option value="">Select Job Duration</option>
                            <option value='lt3months'>Less than 3 Months</option>
                            <option value='lt6months'>Less than 6 Months</option>
                            <option value='1year-over'>1 Year & Over</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="form-group">
                        <label for="work-authorization">Work Authorization</label>
                        <select class="form-control" name="work_authorization">
                            <option value="">Select Work Authorization</option>
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

            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for="posted-after">Job Posted After</label>
                        <div class="input-group updated_atGrp">
                            {{-- <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)"> --}}
                            <input type="date" name="updated_at" placeholder="Click to pick Date" class="form-control" data-input>
                            <div class="input-group-append">
                                <span class="input-group-text input-button" data-toggle><i class="fas fa-calendar"></i></span>
                                {{-- <span class="input-group-text">0.00</span> --}}
                            </div>
                        </div>
                        {{-- <input type="date" name="updated_at" placeholder="Click to pick Date" class="form-control"> --}}
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='joining-by'>Require to Join By</label>
                        <select class="form-control" name="joining">
                            <option value="">Select Joining Preference</option>
                            <option value='immediate'>Immediate</option>
                            <option value='1week'>1 Week</option>
                            <option value='2weeks'>2 Weeks</option>
                            <option value='1month'>1 Month</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='joining-by'>Industry Domain</label>
                        <select class="form-control" name="domain">
                            <option value="">Select Job Domain</option>
                            <option value='aerospace'>Aerospace</option>
                            <option value='automotive'>Automotive</option>
                            <option value='banking'>Banking</option>
                            <option value='BFSI'>BFSI</option>
                            <option value='consumer-FMCG'>Consumer / FMCG</option>
                            <option value='chemicals'>Chemicals</option>
                            <option value='engineering-construction'>Engineering and Construction</option>
                            <option value='energy'>Energy</option>
                            <option value='education'>Education</option>
                            <option value='finance'>Finance</option>
                            <option value='hospitality-leisure'>Hospitality and Leisure</option>
                            <option value='healthcare'>Healthcare</option>
                            <option value='insurance'>Insurance</option>
                            <option value='technology'>Technology</option>
                            <option value='retail'>Retail</option>
                            <option value='travel'>Travel</option>
                            <option value='telecom'>Telecom</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='travel_required'>Travel Required</label><br/>
                        <input type="checkbox" id="travel" name="travel" class="checkbox" {{array_key_exists('travel', $params) && $params['travel'] == 'on' ? 'checked' : ''}}/>
                        <label for="travel" class="switch"></label>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='percentage'>Travel Percentage</label>
                        <input type="number" name="percentage" value="{{array_key_exists('percentage', $params) ? $params['percentage'] : ''}}" placeholder="Travel %" class="form-control"/>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                    <div class="form-group">
                        <label for='equal_opportunity'>EEO-Equal Opportunity Employer</label><br/>
                        <input type="checkbox" id="equal_opportunity_employer" name="equal_opportunity_employer" class="checkbox" {{array_key_exists('equal_opportunity_employer', $params) && $params['equal_opportunity_employer'] == 'on' ? 'checked' : ''}}/>
                        <label for="equal_opportunity_employer" class="switch"></label>
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
            $('.updated_atGrp').flatpickr({wrap: true, maxDate: 'today', disableMobile: "true"});
            $('#resetSearch').on('click', function(){
                document.getElementById('searchForm').reset();
                var inputs = $('#searchForm').find('input');
                $.each(inputs, function(i, v){
                    if($(v).attr('name') == '_token') return false;
                    $(v).val('').removeAttr('checked');
                });
                toastr.info('All Search parameters have been reset!');
            });
            
            $('#travel').on('change', function(e){
                if(!$(this).is(":checked")) $('input[name="percentage"]').val(0);
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
                tags: true,
                ajax: {
                    url: "{{ route('frontend.getSkills') }}",
                    type: "post",
                    dataType: 'json'
                }
            }).on('select2:select', function(){
                $('.select2-search__field').val('');
            });
            
            $('select#locations').select2({
                placeholder: 'Try typing a city, state or zip...',
                minimumInputLength: 1,
                allowClear: true,
                closeOnSelect: false,
                selectOnClose: true,
                scrollAfterSelect: true,
                multiple: true,
                // tags: true,
                ajax: {
                    url: "{{ route('frontend.getLocations') }}",
                    type: "post",
                    dataType: 'json'
                }
            }).on('select2:select', function(){
                $('.select2-search__field').val('');
            });

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