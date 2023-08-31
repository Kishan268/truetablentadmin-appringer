 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $companies->total() !!} {{ trans_choice('Featured Logos', $companies->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $companies->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->