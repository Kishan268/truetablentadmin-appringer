@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

{{-- Hi {{$first_name.' '.$last_name}},

Please verify your email to continue your journey with TrueTalent.


Thank you for signing with --}}
<?php
    if(strpos(notificationTemplates('verification_reminder')->variables, '$first_name') !== false){
        $array = explode(',',notificationTemplates('verification_reminder')->variables);
        echo str_replace($array ,array($first_name,$last_name),notificationTemplates('verification_reminder')->mail_body);
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