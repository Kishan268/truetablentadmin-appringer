@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.candidate_management') . ' | '.__('labels.backend.access.users.rate'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')
{{ html()->modelForm($user, 'POST', route('admin.auth.user.saveEvaluation', $user->id))->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.users.candidate_management')
                        <small class="text-muted">@lang('labels.backend.access.users.rate')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="form-group row px-2">
                        {{ html()->label('Candidate Name')->class('col-md-2 form-control-label') }}

                        <div class="col-md-10 font-weight-bold">
                            {{ $user->full_name }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table" id='ratings'>
                                <thead>
                                    <tr>
                                        <th>Skills & Ratings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($wP != null)
                                                @foreach($wP as $ind => $skill)
                                                    <div class="form-inline row ">
                                                        <div class="col-2 text-capitalize"><label for="rating-{{$skill['id']}}">{{$skill['skill_name']}}</label></div>
                                                        <div class="col-1"><input type="number" readonly name='ratings[{{$skill["id"]}}]' value="{{$skill['remarks'] == null ? 0 : $skill['remarks']}}" id='input-{{$ind}}' ind='{{$ind}}' min="0" max="100" step="0" class="form-control ratingInput" /></div>
                                                        <div class="col-9"><input type="range" value="{{ $skill['remarks'] == null ? 0 :$skill['remarks']}}" id='rating-{{$ind}}' ind='{{$ind}}' class="form-range ratingRange"></div>
                                                    </div>
                                                    <hr/>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row px-2  mt-2">
                        {{ html()->label('Evaluation Feedback')->class('col-md-2 form-control-label text-center') }}
                        
                        <div class="col-md-10 font-weight-bold">
                            <textarea class="form-control" rows="4" placeholder="Enter Evaluation Feedback" name="feedback">{{ $feedback ? $feedback->evaluation_feedback : '' }}</textarea>
                        </div>
                        <!--col-->
                    </div>

                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--row-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->closeModelForm() }}
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

{{-- @push('after-scripts') --}}
<script>
    // const app = new Vue({
    //     el: '#ratings',
    //     data:{
    //         a: 10,
    //         skillMap: "",
    //         ratings: []
    //     },
    //     mounted() {
    //         this.skillMap = ""
    //         console.log(this.skillMap);
    //         console.log('Vue Loaded.');
    //     }
    // });
    $(function(){
        $('.ratingInput').on('change keydown', function(){
            let val = $(this).val();
            if(val > 100) val = 100
            if(val < 0) val = 0
            let ind = $(this).attr('ind');
            $(`#rating-${ind}`).val(val);
        });

        $('.ratingRange').on('change', function(){
            let val = $(this).val();
            let ind = $(this).attr('ind');
            $(`#input-${ind}`).val(val);
        });
    });
</script>
{{-- @endpush --}}
