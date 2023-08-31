@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

{{-- Hi {{$reciepient_first_name.' '.$reciepient_last_name}},

You are trying to add a user whose email id domain and website domain (as registered by your organisation) do not match. In line with our security policy, the TrueTalent Support Team will reach out to you to create your account.


User name is: <b>{{$first_name.' '.$last_name}}</b> <br />
User email is: <b>{{$email}}</b> <br />


Thank you for doing business with  --}}
<?php
    if(strpos(notificationTemplates('user_registration_request')->variables, '$reciepient_first_name') !== false){
        $array = explode(',',notificationTemplates('user_registration_request')->variables);
        echo str_replace($array ,array($reciepient_first_name,$reciepient_last_name,$first_name,$last_name,$email),notificationTemplates('user_registration_request')->mail_body);
    }
?>
<p>
{{ config('app.name') }}!

Best Regards,<br/>

{{ config('app.name') }} Support Team
</p>
@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent