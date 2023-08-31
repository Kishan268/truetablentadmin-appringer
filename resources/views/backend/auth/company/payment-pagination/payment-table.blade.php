@php
    $count = 1;
@endphp
@foreach($payments as $pmt)
    <tr>
        <td>{{ $pmt->id }}</td>
        <td>{{ $pmt->amount }}</td>
        <td>{{ $pmt->transaction_id }}</td>
        <td>
            {{ $pmt->company ? $pmt->company->name:'' }}
        </td>
        <td>
            <a href="{{route('admin.auth.user.show', ['user' => $pmt->user->id])}}" target="_blank" title="@lang('buttons.general.crud.view')">
                {{ $pmt->user->uid }} - {{ $pmt->user->full_name }}
            </a>
        </td>
        <td>{{ date_format( $pmt->created_at,"d-M-Y h:i A") }} ({{ $pmt->created_at->diffForHumans() }})</td>
    </tr>
@endforeach