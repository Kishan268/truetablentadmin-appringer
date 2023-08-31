@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot
{{-- 
Hi {{$first_name.' '.$last_name}},

Your email address and website domain do not match. In line with our security policy, TrueTalent Support Team will reach out to you to create your account.


Your name is: <b>{{$first_name.' '.$last_name}}</b> <br />
Your email is: <b>{{$email}}</b> <br />
Your mobile number is: <b>{{$contact}}</b> <br />
Your company name is: <b>{{$company_name}}</b> <br />
Your company website is: <b>{{$website}}</b> <br />
Your location is: <b>{{$location_name}}</b> <br />
Your company size is: <b>{{$company_size_name}}</b> <br />
Your industry domain is: <b>{{$industry_domain_name}}</b> <br />


Thank you for signing with  --}}
<?php
    if(strpos(notificationTemplates('company_registration_faild')->variables, '$first_name') !== false){
    $array = explode(',',notificationTemplates('company_registration_faild')->variables);
    echo str_replace($array ,array($first_name,$last_name,$email,$contact,$company_name,$website,$location_name,$company_size_name,$industry_domain_name),notificationTemplates('company_registration_faild')->mail_body);
    }
?>
{{ config('app.name') }}!

Best Regards,<br/>
{{ config('app.name') }} Support Team

@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent