@foreach ($companyGigs as $key => $companyGig)
  <tr >
    <td>
          <input type="radio" name="selected_gig" class="form-check-input" id="selected_gig{{$orderId}}" value="{{$companyGig->id}}" data-company-id="{{$companyGig ? $companyGig->id:''}}" data-order="{{$orderId}}"></td>
    <td style="float: left;"><img src="{{ $companyGig->logo ?  App\Helpers\SiteHelper::getObjectUrl($companyGig->logo) : '' }}"  class="mx-2"style="max-width: 30px; max-height: 30px;">{{$companyGig ? $companyGig->company_name : ''}}</td>
    <td>{{$companyGig->title}}</td>
    <td>{{date("d-m-Y", strtotime($companyGig->created_at))}}</td>
    <td>{{@$companyGig->first_name .' '.@$companyGig->last_name}}</td>
  </tr>
@endforeach