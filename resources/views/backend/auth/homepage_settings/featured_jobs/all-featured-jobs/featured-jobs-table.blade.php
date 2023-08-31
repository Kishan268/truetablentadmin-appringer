 @foreach ($jobsDatas as $key => $jobsData)
  <tr >
    <td>
      <div class="form-check form-check-inline">
          <input type="radio" name="selected_job" class="form-check-input job-order-id" id="selected_job{{$orderId}}" value="{{$jobsData->id}}" data-company-id="{{$jobsData->company_details ? $jobsData->company_details->id:''}}" data-order="{{$orderId}}"></td>
      </div>
    <td>{{$jobsData->company_name ? $jobsData->company_name : ''}}</td>
    <td>{{$jobsData->title}}</td>
    <td>{{date("d-m-Y", strtotime($jobsData->created_at))}}</td>
    <td>{{@$jobsData->first_name .' '.@$jobsData->last_name}}</td>
  </tr>
@endforeach