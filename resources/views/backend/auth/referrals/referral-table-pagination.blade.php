 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $referrals->total() !!} {{ trans_choice('Referrals', $referrals->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $referrals->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->