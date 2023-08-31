 @foreach($referrals as $referral)
    <tr>
        <td>{{ $referral->id }}</td>
        <td>{{ $referral->program_name ? $referral->program_name :''}}</td>
        <td>{{ $referral->user_type ? ucfirst($referral->user_type) :''}}</td>
        <td>{{ $referral->limit_per_user ? $referral->limit_per_user : 'Unlimited' }}</td>
        <td>{{ date('d-m-Y', strtotime($referral->start_date)) }}</td>
        <td>{{ date('d-m-Y', strtotime($referral->end_date)) }}</td>
        <td>{{ $referral->amount ? $referral->amount : 0 }}</td>

        <td>{{ \Carbon\Carbon::parse($referral->updated_at)->diffForHumans() }}</td>
        <td>{{ date('d-m-Y', strtotime($referral->created_at)) }}</td>
        
        <td>
            @can('update_referral')
             <a href="{{route('admin.auth.referral.referral-edit',$referral->id)}}" name="edit"  class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top">Edit</a>
            @endcan
        </td>
    </tr>
@endforeach