 @foreach ($companies as $key => $company)
  <tr >
    <td>
      {{-- <div class="form-check form-check-inline"> --}}
          <input type="radio" name="selected_job" class="form-check-input" id="selected_job{{$orderId}}" value="{{$company->id}}" data-company-id="{{$company ? $company->id:''}}" data-order="{{$orderId}}" ></td>
      {{-- </div> --}}
    <td width="30%">
    	@if($company->logo != null && $company->logo != "")
    		<img src="{{$company->logo}}">
    	@else
    		N/A
    	@endif

    </td>
    <td>{{$company ? $company->name : ''}}</td>
  </tr>
  @endforeach
