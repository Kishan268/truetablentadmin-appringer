<div class="row mt-4">
    <div class="col-lg-12">
           <span class="page-count"> {!! $users->total() !!} {{ trans_choice('Users', $users->total()) }}</span>
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $users->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->