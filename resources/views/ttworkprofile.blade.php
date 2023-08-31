<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body,table,h4,h2,span,p{
	        font-family: 'Plus Jakarta Sans',sans-serif !important;
	    }
		.container {
			display:block;
			width:100%;

		}
		.column {
			float: left;
			padding-bottom: 100%;
			margin-bottom: -100%;
		}
		.layoff-container {
		    background-color: rgba(253,129,94,.05);
		    border-radius: 19px;
		    text-align: center;
		    width: 25%;
		}
		.layoff-container p {
		    color: #fd815e!important;
		    padding: 6px;
		    font-size: 8px;
		}
		.detail-p{
			font-size: 10px;
		    color: #263238;
		    /*font-weight: 600;*/
		    margin-bottom: 5px;
		}
		.skill-h{
			font-size: 12px;
		    font-weight: 700;
		    color: #263238;
		}
		.skill-name{
		    font-size: 10px;
		    /*font-weight: 500;*/
		    color: #263238;
		}

		.general-details{
		    background: rgba(20,188,154,.05);
		    border-radius: 12px;
		    padding: 8px 15px !important;
		    margin-right: 10px;
		}

		.general-details p {
		    color: #9398a1;
		    font-size: 7px!important;
		    margin-top: 0px !important;
		    margin-bottom: 0px !important;
		}

		.general-details span {
		    color: #263238;
		    font-size: 9px;
		    /*font-weight: 700;*/
		}

		.detail-main-h{
			font-size: 12px;
    		/*font-weight: 600;*/
    		color: #263238;
		}
		.detail-main-title{
			margin-top: 12px;
    		font-size: 10px;
    		/*font-weight: 800;*/
		}
		.detail-main-description{
			margin-top: 12px;
		    font-size: 10px;
		    color: #2c2c2c;
		}
		.blue-text {
		    color: #4b8bff !important;
		}
		.additional-info{
		    background: #f2f2f2;
		    border-radius: 19px;
		    padding: 4px 8px;
		    margin-right: 20px;
		}
		.additional-info p{
			letter-spacing: 0;
		    color: #263238;
		    font-size: 7px !important;
		    /*font-weight: 600;*/
		}

		.skills-div {
		  page-break-inside: avoid !important;
		}

		.skills-div > *:first-child {
		  page-break-before: avoid !important;
		}

		.skills-div > *:last-child {
		    page-break-after: avoid !important;
		}

		.pull-in-screen{
			margin-left: 750px !important
		}

		#watermark {
            position: fixed;

            /** 
                Set a position in the page for your image
                This should center it vertically
            **/
            bottom:   13cm;
            /*left:     2cm;*/

            /** Change image dimensions**/
            /*width:    10cm;*/
            height:   4cm;

            /** Your watermark should be behind every content**/
            z-index:  -1000;
        }

		@font-face {
			font-family: 'Plus Jakarta Sans',sans-serif;
			src: url(https://ttadmin.appringer.co.in/PlusJakarta.ttf) format('truetype');
		}
	    @media print {
	        body{
		    	font-family: 'Plus Jakarta Sans',sans-serif !important;
		    }
	    }

	    
	</style>
</head>
<body>

	<div id="watermark">
        <img src="{{url('watermark.png')}}" height="100%" width="100%" />
    </div>
	<div class="container">
		@php
			$char_per_line = 121;
			$c = 0;
			$line_threshhold = 37;
			$summary_str_len = strlen(strip_tags($user->userWorkProfile->summary));
			$c = ($summary_str_len % $char_per_line == 0) ? intdiv($summary_str_len , $char_per_line) : (intdiv($summary_str_len , $char_per_line) + 1);
			$is_page_breaked = false;
		@endphp
		<div class="column skills-div"  style="max-width: 20%;page-break-inside: avoid !important;margin-left: 600px;">
			
	    	<div class="skills">
          		<h2 class="skill-h">Skills</h2>
          		<div style="width: 100%;border-top: 1px solid #CFCFCF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

          		<table style="width: 100%" cellspacing="0" cellpadding="0" border="0">
          			<tbody>
          				@if(isset($user->userWorkProfileDetail['skill']) && count($user->userWorkProfileDetail['skill']) > 0)
          					@foreach($user->userWorkProfileDetail['skill'] AS $skill)
		          				<tr>
		          					<td width="10%" style="vertical-align: center;">
		          						<img style="margin-right: 15px;margin-top: 8px;width: 5px" src="{{url('rectangle.svg')}}">
		                      		
		          					</td>
		          					<td class="skill-name">
		          						{{ App\Helpers\SiteHelper::getMasterDataName( $skill->skill_id) }}
		          					</td>
		          				</tr>
		          			@endforeach
		          		@endif

          				

          			</tbody>
          		</table>
          	</div>
			          
		</div>
		<!--  -750px  -->
		<div class="column" style="max-width: 80%;">
			<table style="width:100%;margin-bottom: 20px;" cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td width="10%" style="width: 20%">
							<div class="column img-border">
			                	<img style="border-radius: 50%;margin-right: 20px" height="100px" width="100px" src="{{ $user->avatar_location ?? url('default_avatar.svg') }}" data-holder-rendered="true" alt="" />
			              	</div>
						</td>
						<td width="52%">
	                  		<p style="font-size: 10px;color: #9398a1;margin-bottom: 10px;">TrueTalent ID: TT1000{{$user->id}}</p> 
		                  		
		                    <h2 style="font-size: 14px;color: #263238;font-weight: 700;">{{ $user->full_name }}</h2>
		                    <div class="layoff-container"> 
		                    	@if($user->userWorkProfile->layoff == '1')
		                    		<p>Impacted by layoff</p>
		                    	@else
		                    		<p style="background-color: #fff !important;"></p>
		                    	@endif
		                    </div>
						</td >
						<td width="22%" style="vertical-align: top">
							<p style="font-size: 10px;color: #9398a1;margin-right: 12px;margin-top: 12px">Last Updated: {{date("d/m/Y", strtotime($user->updated_at))}}
							</p>
						</td>
					</tr>
					
				</tbody>
			</table>

			<table style="width: 100%;" cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td width="80%">
							<span style="font-size: 10px;padding-right: 10px">
								{!! strip_tags($user->userWorkProfile->summary) !!}
							</span>
						</td>
					</tr>
					
				</tbody>
			</table>

			<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td >
							<img style="width: 28px;margin-top: 11px;margin-right: 20px" data-holder-rendered="true" src="{{url('email.svg')}}">
						</td>
						<td>
							<p class="detail-p">{{ $user->email }}</p>
						</td>
					</tr>
					<tr>
						<td>
							<img style="width: 28px; margin-right: 20px;margin-top: 11px;" data-holder-rendered="true" src="{{url('phone.svg')}}">
						</td>
						<td>
							<p class="detail-p">{{ $user->userWorkProfile->contact_number ?? "N/A" }}</p>
						</td>
					</tr>
					<tr>
						<td>
							<img style="width: 28px; margin-right: 20px;margin-top: 11px" data-holder-rendered="true" src="{{url('marker.svg')}}">
						</td>
						<td>
							<p class="detail-p">Current: {{ $user->userWorkProfile->location_name ?? "N/A" }} | Preferred: {{ $user->preferred_location_data != '' ? $user->preferred_location_data : "Any" }}</p>
						</td>
					</tr>
					
				</tbody>
			</table>

			<table style="margin-top: 20px" cellspacing="10" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td class="general-details">
							<p>Experience</p>
							<span>{{ App\Helpers\SiteHelper::getCandidateExperienceText($user->userWorkProfile->total_experience) }}</span>
						</td>
						<td class="general-details">
							<p>Expected Salary</p>
							<span>{{ $user->min_salary != null ? number_format($user->min_salary) : "N/A" }}</span>
						</td>
						<td class="general-details">
							<p>Joining Preference</p>
							<span>{{$user->userWorkProfile->joining_preference_name !== null && $user->userWorkProfile->joining_preference_name !== '' ? $user->userWorkProfile->joining_preference_name : 'N/A'}}</span>
						</td>
						<td class="general-details">
							<p>Travel</p>
							<span>{{$user->is_telecommute == '1' ? 'Ready for Travel' : 'Not Ready for Travel'}}</span>
						</td>

						<td class="general-details">
							<p>Job Preference</p>
							<span>
								@if(isset($user->preferred_job_types) && isset($user->preferred_gig_types) && count($user->preferred_job_types) > 0 && count($user->preferred_gig_types) > 0)

									Jobs and Gigs
								@elseif(isset($user->preferred_job_types) && count($user->preferred_job_types) > 0)
									Jobs
								@elseif(isset($user->preferred_gig_types) && count($user->preferred_gig_types) > 0)
									Gigs
								@else
									N/A
								@endif
							</span>
						</td>
						
					</tr>
				</tbody>
			</table>

			<table style="margin-top: 20px;" cellspacing="10" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td class="general-details">
							<p>Preferred Job Type</p>
							<span>{{$user->preferred_job_type_data ? $user->preferred_job_type_data : 'N/A'}}</span>
						</td>
					</tr>
				</tbody>
			</table>

			@foreach($headings AS $key => $heading)
				
				@if(($c >= $line_threshhold || $c + 4 > $line_threshhold) && !$is_page_breaked)
					</div>
					<div style="page-break-before: always;"></div>
					<div class="column" style="max-width: 80%;">
					@php
						$c=0;
						$is_page_breaked = true;
					@endphp
				@endif

				@if(count($heading) > 0)
					<h2 class="skill-h">{{ $key }}</h2>
		      		<div style="width: 100%;border-top: 1px solid #CFCFCF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		      		@php
						$c = $c +4;
					@endphp


	      			@foreach($heading AS $data)
	      				@php
	      					$summary_str_len = strlen(strip_tags($data->description));
	      					$summary_line_count = ($summary_str_len % $char_per_line == 0) ? intdiv($summary_str_len , $char_per_line) : (intdiv($summary_str_len , $char_per_line) + 1);
	      				@endphp
						@if($c + 2 > $line_threshhold && !$is_page_breaked)
							</div>
							<div style="page-break-before: always;"></div>
							<div class="column" style="max-width: 80%;">
							@php
								$c=0;
								$is_page_breaked = true;
							@endphp
						@endif
						<h2 style="margin-bottom: 10px;" class="detail-main-h">{{ $data->from_date != null ? date("Y", strtotime($data->from_date)) : '' }} {{$data->to_date != null ? ' - '.date("Y", strtotime($data->to_date)) : ($data->is_present == '1' ? ' - now' : '')}} {{ $data->title ? ' - '.$data->title : '' }}</h2>
						@php
							$c = $c +2;
						@endphp

						@if($c + 2 >= $line_threshhold && !$is_page_breaked)
							</div>
							<div style="page-break-before: always;"></div>
							<div class="column" style="max-width: 80%;">
							@php
								$c=0;
								$is_page_breaked = true;
							@endphp
						@endif

						<p style="margin-bottom: 10px;" class="blue-text detail-main-title">{{ $data->awarded_by }}</p>
						@php
							$c = $c +2;
						@endphp

						@if($c + $summary_line_count >= $line_threshhold && !$is_page_breaked)
							</div>
							<div style="page-break-before: always;"></div>
							<div class="column" style="max-width: 80%;">
							@php
								$c=0;
								$is_page_breaked = true;
							@endphp
						@endif
						<p style="margin-bottom: 25px;" class="detail-main-description">{!! strip_tags($data->description) !!}</p>
						@php
									
							$c = $c + $summary_line_count;
						@endphp
						
					@endforeach
				@endif
			

			@endforeach

			@if($c >= $line_threshhold && !$is_page_breaked)
				</div>
				<div style="page-break-before: always;"></div>
				<div class="column" style="max-width: 80%;">
				@php
					$c=0;
					$is_page_breaked = true;
				@endphp
			@endif

			@php
				$c = $c +3;
			@endphp

			@if($c >= $line_threshhold && !$is_page_breaked)
				</div>
				<div style="page-break-before: always;"></div>
				<div class="column" style="max-width: 80%;">
				@php
					$c=0;
					$is_page_breaked = true;
				@endphp
			@endif

			<h2 class="skill-h" style="width: 100%">Additional Information</h2>
      		<div style="width: 100%;border-top: 1px solid #CFCFCF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
      		
      		
			<table style="margin-top: 10px;" cellspacing="10" cellpadding="0" border="0">
				<tbody>
					<tr>
						@if($user->userWorkProfile->her_career_reboot == '1')
							<td class="additional-info">
								<p>Her Career Reboot</p>
							</td>
						@endif
						@if($user->userWorkProfile->differently_abled == '1')
							<td class="additional-info">
								<p>Differently Abled</p>
							</td>
						@endif
						@if($user->userWorkProfile->armed_forces == '1')
							<td class="additional-info">
								<p>Armed Forces</p>
							</td>
						@endif
						
					</tr>
				</tbody>
			</table>
		    
		</div>

		
	</div>
</body>
</html>