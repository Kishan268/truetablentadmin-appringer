@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot

User Email




Best Regards,<br />
Maya<br />
Customer Relationship Manager<br />

@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent