 <div class="row mt-4">
    <div class="col-lg-12">
           <span class="custom-buttons">  {!! $companies->total() !!} {{ trans_choice('Companies', $companies->total()) }}</span>
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $companies->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->