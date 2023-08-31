 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $companyGigs->total() !!} {{ trans_choice('Company Gigs', $companyGigs->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $companyGigs->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->