 <div class="row mt-4">
    <div class="col-lg-12">
            {!! $system_configs->total() !!} {{ trans_choice('System Configs', $system_configs->total()) }}
        <div class="pull-right custom-buttons">
            <div class="btn-group">
                {!! $system_configs->render() !!}
            </div>
        </div>
    </div> 
</div><!--row-->