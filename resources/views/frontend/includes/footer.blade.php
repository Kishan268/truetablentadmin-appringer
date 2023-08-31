<footer class="app-footer p-3 mt-5">
	<div class="row">
	    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mobCenter">
	        <strong>
	        	{{-- @lang('labels.general.copyright')  --}}
	        	&copy; {{ date('Y') }}
	            <a href="{{ substr($settings->company_website, 0, 4) == 'http' ? $settings->company_website : 'http://'.$settings->company_website}}">
	                {{$settings->company_name}}
	            </a>
	        </strong> @lang('strings.frontend.general.all_rights_reserved')
	    </div>
	    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 text-center">
	       
	    </div>
	    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 footerJustify" style="font-size: 1.1rem;">
			@if($settings->fb != null) <a href="{{$settings->fb}}" target="_blank" title="Like us on Facebook" class="mt-1 mx-1" style="text-decoration: underline;"><i class="fab fa-facebook-square"></i></a> @endif
			@if($settings->twitter != null) <a href="{{$settings->twitter}}" target="_blank" title="Follow us on Twitter" class="mt-1 mx-1" style="text-decoration: underline;"><i class="fab fa-twitter-square"></i></a> @endif
			@if($settings->linkedin != null) <a href="{{$settings->linkedin}}" target="_blank" title="Connect on LinkedIn" class="mt-1 mx-1" style="text-decoration: underline;"><i class="fab fa-linkedin"></i></a> @endif
	    </div>
	</div>
</footer>
