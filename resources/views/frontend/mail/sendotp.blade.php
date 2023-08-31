@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot


{{-- Dear {{ $user_name }}.<br /><br />
We understand you have requested a reset of your password.<br />
The OTP for resetting your password is {{$otp}}.<br /><br />
We request that you not share this OTP or your password with anyone.<br />
PS: If you have not requested a password change, please ignore this email and login using your existing password.

Best Regards,<br/> --}}
<?php
    if(strpos(notificationTemplates('send_otp')->variables, '$user_name') !== false){
        $array = explode(',',notificationTemplates('send_otp')->variables);
        echo str_replace($array ,array($user_name,$otp),notificationTemplates('send_otp')->mail_body);
    }
?>
<p>
TrueTalent Support Team<br/>
support@truetalent.io</p>


@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent