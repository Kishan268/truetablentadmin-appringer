@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot
<?php
    if(strpos(notificationTemplates('company_exist_admin')->variables, '$company_admin_name') !== false){
    $array = explode(',',notificationTemplates('company_exist_admin')->variables);
    echo str_replace($array ,array($company_admin_name,$user_name,$user_email),notificationTemplates('company_exist_admin')->mail_body);
    }
?>
{{-- Hi {{ $company_admin_name }},<br /><br />
We are reaching out to you to let you know that another user from your company has been trying to join our platform. Since you are the admin for your company, we request you to active the user for them to start using our services and hire the best talent.<br />

The details of the user are given below:<br />
Name of the new user: {{ $user_name }}<br />
Email of the new user: {{ $user_email }}<br />



Best Regards,<br /> --}}
<p>
TrueTalent Support<br />
</p>
@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent