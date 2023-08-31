@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ route('frontend.index') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo">
            
        </a>
    @endcomponent
@endslot
Hi,
A request for buying TT-Cash has been received on {{ env('APP_NAME') }}.
@component('mail::panel')
<b>Company Name</b>: {{$company->name}}<br/>
<b>Amount</b>: {{$amount}}<br/>
<b>Requested By</b>: {{$user->full_name}} ({{$user->uid}})
@endcomponent
Best Regards,<br/>
{{ config('app.name') }} Team
@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent
@endcomponent