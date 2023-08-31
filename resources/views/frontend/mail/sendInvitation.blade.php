@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot
{{-- Dear {{ $referee_name }}.<br /><br /> --}}
@if($target_audience == 'companies')
	
{{-- We have been using <span style="color: #14BC9A">TrueTalent</span> platform to fulfill our current talent needs and have seen a significant value add to our hiring process both in terms of time and cost.<br /><br />

We would love to see you there and are sure <span style="color: #14BC9A">TrueTalent’s</span> innovative platform offerings will positively impact your hiring strategies.<br /><br />

So do click on the below link and register to search for some of the best talent in the industry.<br /><br />

PS: Do not miss out on clicking this link, since you registering through the below link will not just open up a whole lot of new features for us, but will also help you get pro features at a highly discounted price because of our referral code.<br /><br />
	 --}}
<?php
    if(strpos(notificationTemplates('send_invitation_comapany')->variables, '$referee_name') !== false){
        $array = explode(',',notificationTemplates('send_invitation_comapany')->variables);
        echo str_replace($array ,array($referee_name),notificationTemplates('send_invitation_comapany')->mail_body);
    }
?>
@else
	
{{-- I have come across some exciting job opportunities with companies of different sizes on <span style="color: #14BC9A">TrueTalent</span> and I strongly believe your skills & experience matches a number of those. Besides, I believe this could be a great career move for you in applying to them.<br /><br />

Please click on the below link and register on <span style="color: #14BC9A">TrueTalent</span> to apply for these fantastic opportunities.<br /><br />

PS: Do not miss out on clicking this link, since you applying for jobs through the link will help me earn some great rewards. You too could earn extraordinary rewards by referring friends once you are on the <span style="color: #14BC9A">TrueTalent</span> platform.<br /><br /> --}}
<?php
    if(strpos(notificationTemplates('send_invitation_other')->variables, '$referee_name') !== false){
        $array = explode(',',notificationTemplates('send_invitation_other')->variables);
        echo str_replace($array ,array($referee_name),notificationTemplates('send_invitation_other')->mail_body);
    }
?>

@endif

<a href="{{$link}}" style="word-break: break-all;">{{$link}}</a><br /><br />

Thank you.<br/>
{{$referrer_name}}<br/>


@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp <span style="color: #14BC9A">{{ config('app.name') }}</span></center>
@endcomponent

@endcomponent