@php 
$count = 1;
@endphp
@foreach($notifications as $notification)
    <tr>
        <td>{{ $count++}}</td>
        <td>{{ $notification->name }}</td>
        <td>{{ $notification->subject}}</td>
        <td>{{ $notification->is_mail_enabled ? 'Yes' : 'No'}}</td>
        <td>{{ $notification->is_sms_enabled ? 'Yes' : 'No'}}</td>
        <td>{{ $notification->is_wa_enabled ? 'Yes' : 'No'}}</td>
        <td>
            @can('update_notification')
                <a href="{{route('admin.auth.notification.edit',$notification->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
            @endcan
        </td>
    </tr>
@endforeach