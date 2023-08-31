

<?php
    if(strpos(notificationTemplates('contact_us')->variables, '$name') !== false){
        $array = explode(',',notificationTemplates('contact_us')->variables);
        echo str_replace($array ,array($request->name,$request->email,$request->phone ?? 'N/A',$request->company_name ?? 'N/A',$request->message),notificationTemplates('contact_us')->mail_body);
    }
?>

{{-- <p>@lang('strings.emails.contact.email_body_title')</p>

<p><strong>@lang('validation.attributes.frontend.name'):</strong> {{ $request->name }}</p>
<p><strong>@lang('validation.attributes.frontend.email'):</strong> {{ $request->email }}</p>
<p><strong>@lang('validation.attributes.frontend.phone'):</strong> {{ $request->phone ?? 'N/A' }}</p>
<p><strong>@lang('validation.attributes.frontend.company_name'):</strong> {{ $request->company_name ?? 'N/A' }}</p>
<p><strong>@lang('validation.attributes.frontend.message'):</strong> {{ $request->message }}</p>
 --}}