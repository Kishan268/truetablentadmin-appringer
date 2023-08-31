 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $jobs->total() !!} {{ trans_choice('Jobs', $jobs->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $jobs->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->