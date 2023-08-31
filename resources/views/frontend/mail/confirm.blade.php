@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

@if($is_company_user && $is_company_user != null)

<?php
    if(strpos(notificationTemplates('company_register')->variables, '$name') !== false){
        $array = explode(',',notificationTemplates('company_register')->variables);
        echo str_replace($array ,array($name),notificationTemplates('company_register')->mail_body);
    }
?>

{{-- Dear {{$name}}.<br /><br />
It’s our pleasure to on-board you as a client user on <a href="{{ env('FRONTEND_URL','https://truetalent.io/') }}">TrueTalent.io</a>, India's First <b>RaaS</b> (Recruitment as a Service) Talent Search Platform.<br /><br />
Your registration process is successfully completed, and you are all set to search for the best candidates on TrueTalent platform.<br /><br />
Our candidate search feature, including viewing candidates’ profiles and contacting them, is <b>absolutely free of charge.</b><br /><br />
Besides searching for candidates, you could also post your job requirements <b>free of charge.</b><br /><br />
However we have great ways to highlight your organisation and your job postings on the homepage of TrueTalent, that gets <b>25X</b> higher traffic, at a small cost. In case you are keen on exploring these features you are requested to email us at <a href="mailto:maya@truetalent.io">maya@truetalent.io</a><br />
We look forward to a successful hiring journey for your organisation on the TrueTalent platform. --}}
@elseif($evaluator && $evaluator != null)

{{-- code for candidate register.... --}}
{{-- Hi {{$name}},<br />
Welcome to <b>TrueTalent</b> - India's first <b>RaaS</b> (Recruitment as a Service) Platform. We are excited to have you on board.<br /><br />
We look forward to you exploring our portal and help campanies to find the best candidates.<br /><br />
I would love to hear what you think of TrueTalent and if there is anything we can improve. If you have any questions, please reply to this email. I will be happy to help!<br /><br /> --}}
<?php
    if(strpos(notificationTemplates('evaluator_register')->variables, '$name') !== false){
        $array = explode(',',notificationTemplates('evaluator_register')->variables);
        echo str_replace($array ,array($name),notificationTemplates('evaluator_register')->mail_body);
    }
?>
@if($email_otp != null)
OTP to verify email is {{$email_otp}}
@endif
@else
<?php
    if(strpos(notificationTemplates('candidates_register')->variables, '$name') !== false){
        $array = explode(',',notificationTemplates('candidates_register')->variables);
        echo str_replace($array ,array($name),notificationTemplates('candidates_register')->mail_body);
    }
?>
{{-- Hi {{$name}},<br />
Welcome to <b>TrueTalent</b> - India's first <b>RaaS</b> (Recruitment as a Service) Platform. We are excited to have you on board.<br /><br />
We look forward to you exploring our portal and finding the best job that suit your skills.<br /><br />
I wish you all the best in your job search!<br /><br />
I would love to hear what you think of TrueTalent and if there is anything we can improve. If you have any questions, please reply to this email. I will be happy to help!<br /><br /> --}}
@if($email_otp != null)
OTP to verify email is {{$email_otp}}
@endif
@endif


@if($password != null)
Your login password is: <b>{{$password}}</b>
@endif

Click the button below to confirm your account.

@component('mail::button', ['url' => env('FRONTEND_URL')."/login?url=".$url])
Confirm
@endcomponent

Having trouble in clicking the "Confirm Account" button?<br/>
Copy and paste the URL below into your web browser - 


@component('mail::panel')
<a href="{{env('FRONTEND_URL')."?url=".$url}}" style="word-break: break-all;">{{env('FRONTEND_URL')."/login?url=".$url}}</a>
@endcomponent

Thank you for signing with {{ config('app.name') }}!

Best Regards,<br/>
@if($is_company_user && $is_company_user != null)
Maya<br />
Corporate Relationship Manager
@else
<b>Asha</b><br />
User Relationship Manager
@endif

@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent
