@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <a href="{{ env('FRONTEND_URL') }}" class="navbar-brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="{{ env('APP_NAME') }} Logo"></a>
    @endcomponent
@endslot


Hi {{ $user->full_name }}.<br /><br />
Company Name 		: {{ App\Config\AppConfig::getInvoiceCompanyName() }}<br />
Company GST Number	: {{ App\Config\AppConfig::getCompanyGSTNumber() }}<br /><br />
Below are the details of your last payment.<br /><br />
Payment Id 		: {{$payment_id}}.<br />
Amount Credited	: {{$currency .' '. number_format($total,2)}}<br />
GST 			: {{$currency .' '. number_format($gst,2)}}<br />
Grand Total 	: {{$currency .' '. number_format($grand_total,2)}}<br /><br />

Best Regards,<br/>
TrueTalent Support Team<br/>
support@truetalent.io


@component('mail::footer')
<center>All rights reserved &copy; @php echo date('Y');@endphp {{ config('app.name') }}</center>
@endcomponent

@endcomponent