<div class="row p-1 justify-content-center">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
        <h4>Create WorkProfile</h4>
        <small class="text-danger">*Please complete the steps below for recruiters to view your profile.</small>
        <div class="row mt-2">
            <div class="col">Work-Profile Completion Status</div>
            <div class="col">
                <div class="progress">
                    @if($user->WorkProfile['completenessLevel'])
                        @php $progress = 33 * $user->WorkProfile['completenessLevel'] + 1; @endphp
                    @else
                        @php $progress = 0; @endphp
                    @endif
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress  }}%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-12 col-xm-12 col-md-6 col-lg-6 col-xl-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">1. Upload Résumé 
                        @if($user->workProfile()->count() && strlen($user->WorkProfile['cvLink']) > 0)
                            <span class="float-right mr-5">
                                <small>
                                    (<a href="{{ route('frontend.candidate.getResume') }}" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a> | 
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#resumeModal"><i class="fas fa-upload"></i> Change
                                    </a>)
                                </small>
                                <span class="text-success"><i class="fas fa-check"></i> Done</span></span>
                        @else
                            <a class="float-right mr-5" href="javascript:void(0);" data-toggle="modal" data-target="#resumeModal">Click to upload</a>
                        @endif
                    </li>
                    <li class="list-group-item">2. Complete Personal Profiling 
                        @if($user->workProfile()->count() && $user->WorkProfile['completenessLevel'] >= 2)
                            <a href="{{ route('frontend.candidate.personalProfile') }}" class="text-success float-right mr-5"><i class="fas fa-check"></i> Done</a>
                        @elseif($user->workProfile()->count() && $user->WorkProfile['completenessLevel'] >= 1)
                            <a href="{{ route('frontend.candidate.personalProfile') }}" class="text-danger float-right mr-5">Pending</a>
                        @else
                            <a href="javascript:void(0);" onclick="toastr.warning('Please upload your Résumé before proceeding to your Personal profile!');" class="text-danger float-right mr-5">Pending</a>
                        @endif
                    </li>
                    <li class="list-group-item">3. AI Driven Profiling 
                        @if($user->workProfile()->count() && $user->WorkProfile['completenessLevel'] >= 3)
                            <a href="javascript:void(0)" class="text-success float-right mr-5"><i class="fas fa-check"></i> Done</a>
                        @else
                            <a href="javascript:void(0)" class="text-danger float-right mr-5">Pending</a>
                        @endif
                    </li>
                    
                    {{--  <li class="list-group-item">4. TT Expert Profiling 
                        @if($user->workProfile()->count() && $user->WorkProfile['completenessLevel'] >= 4)
                            <a href="javascript:void(0)" class="text-success float-right mr-5"><i class="fas fa-check"></i> Done</a>
                        @else
                            <a href="{{ route('frontend.candidate.scheduleExpertProfiling') }}" class="text-danger float-right mr-5">Pending</a>
                        @endif
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="resumeModal" tabindex="-1" role="dialog" aria-labelledby="resumeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumeLabel">Upload Résumé</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('frontend.candidate.setResume') }}" enctype="multipart/form-data">
            @csrf
                <div class="modal-body p-5">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="resume" id="resumeFile" accept=".pdf">
                        <label class="custom-file-label" for="customFile">Choose résumé file:</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('after-scripts')
<script type="text/javascript">
    $(function(){
        $('input[type="file"]').on('change', function(e){
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').text(fileName);
        });
        @if(session()->get('resume_done') )
            setTimeout(() => {
                window.location = '/personalProfile';
            }, 5000);
            @php session()->forget('resume_done'); @endphp
        @endif
    });
</script>
@endpush