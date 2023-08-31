@foreach($users as $user)
    <tr>
        {{-- <td>{{ $user->uid }}</td> --}}
        <td>{{ $user->full_name }}</td>
        <td>{{ $user->email }}</td>
        <td>@include('backend.auth.user.includes.confirm', ['user' => $user,'type' => $type])</td>
        <td>{{ $user->updated_at->diffForHumans() }}</td>
        <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
        <td class="btn-td">@include('backend.auth.user.includes.actions', ['user' => $user,'type' => $type])</td>
    </tr>
@endforeach