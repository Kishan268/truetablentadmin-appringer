 <div class="row mt-4">
    <div class="col-lg-12">
           <span class="custom-buttons">  {!! $notifications->total() !!} {{ trans_choice('notifications', $notifications->total()) }}</span>
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $notifications->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->