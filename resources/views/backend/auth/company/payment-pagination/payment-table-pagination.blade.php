 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $payments->total() !!} {{ trans_choice('Payments', $payments->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $payments->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->