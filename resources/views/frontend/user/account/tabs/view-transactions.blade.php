<ul class="list-group list-group-horizontal-md my-2 justify-content-center">
    <li class="list-group-item pr-4">Available TT Cash: <span class="badge badge-success">{{$logged_in_user->remaining_views}}</span></li>
</ul>
<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
            <th>Candidate Id</th>
            <th>Quantity</th>
            <th>Transaction Type</th>
            <th>Balance</th>
            <th>Date/time</th>
        </thead>
        <tbody>
            @foreach ($logged_in_user->view_transactions as $tr)
                <tr>
                    <td>{!! $tr->candidate_id == 0 ? 'NA' : "<a target='_blank' href='/WorkProfile/".$tr->candidate_id."' title='Click to view Candidate Profile'>".$tr->candidate->uid."</a>" !!}</td>
                    <td>{{ $tr->amount }}</td>
                    <td>{{ $tr->candidate_id == 0 ? $tr->type : 'View Used' }} 
                        @if($tr->candidate_id != 0)
                        <hr/>Valid Until: {{ $tr->valid_until }}
                        @endif
                    </td>
                    <td>{{ $tr->remaining }}</td>
                    <td>{{ $tr->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
