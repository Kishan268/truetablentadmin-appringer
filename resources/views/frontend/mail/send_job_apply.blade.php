@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

{{-- Dear {{ $recruiter_name }}.<br /><br />
Your job posting has new applicants. Please visit the link below to view the new applicants and contact them if you find their resume suitable for your open position(s).<br /><br /> --}}
<?php
    if(strpos(notificationTemplates('send_job_apply')->variables, '$recruiter_name') !== false){
        $array = explode(',',notificationTemplates('send_job_apply')->variables);
        echo str_replace($array ,array($recruiter_name),notificationTemplates('send_job_apply')->mail_body);
    }
?>

<a href="{{$link}}" style="word-break: break-all;">{{$link}}</a><br /><br />
<p>
Happy hiring!<br/>
TrueTalent Support Team<br/>
support@truetalent.io
</p>

@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent