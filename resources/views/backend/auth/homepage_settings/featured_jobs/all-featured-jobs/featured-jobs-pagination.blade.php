 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $jobsDatas->total() !!} {{ trans_choice('Featured Jobs', $jobsDatas->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $jobsDatas->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->