@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

{{-- Dear {{$name}},<br /><br />
You have been added as a user to <a href="{{ env('FRONTEND_URL','https://truetalent.io/') }}">TrueTalent.io</a> by your company admin, {{$company_admin}}.<br /><br />
It’s our pleasure to on-board you as a client user on <a href="{{ env('FRONTEND_URL','https://truetalent.io/') }}">TrueTalent.io</a>, India's First <b>RaaS</b> (Recruitment as a Service) Talent Search Platform.<br /><br />
You are all set to search for the best candidates on TrueTalent platform by using the below credentials.<br /><br />

Your login email is: <b>{{$email}}</b> <br />
Your login password is: <b>{{$password}}</b><br /><br />

Our candidate search feature, including viewing candidates’ profiles and contacting them, is <b>absolutely free of charge.</b><br /><br />
Besides searching for candidates, you could also post your job requirements <b>free of charge.</b><br /><br />
However we have great ways to highlight your organization and your job postings on the homepage of TrueTalent, that gets <b>25X</b> higher traffic, at a small cost. In case you are keen on exploring these features you are requested to email us at <a href="mailto:maya@truetalent.io">maya@truetalent.io</a><br /><br />
We look forward to a successful hiring journey for your organization on the TrueTalent platform.<br /><br />

Click the button below to login into your account. --}}
<?php
    if(strpos(notificationTemplates('user_created')->variables, '$name') !== false){
        $array = explode(',',notificationTemplates('user_created')->variables);
        echo str_replace($array ,array($name,$company_admin,$email,$password),notificationTemplates('user_created')->mail_body);
    }
?>


@component('mail::button', ['url' => $url])
login
@endcomponent

<p>
Best Regards,<br />
Maya<br />
Customer Relationship Manager<br /></p>

@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent