@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.popup_management'))

@push('after-styles')
<style>
    .stats{
        font-size: 4rem;
    }
    .redirectTo{
        cursor: pointer;
    } 
    .select2{
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        
/*         padding: 1px 17px; */
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{route('admin.auth.notification.store')}}" id="save-notification">
@csrf
@method('POST')
<input type="hidden" name="id" value="{{isset($notifications->id) ? $notifications->id :''}}" />
<div class="row">
    <div class="col">

        <div class="card">
            <!--card-header-->
            <div class="card-body">
                <div class="row mt-3">
                    <div class=" col-md-4 mt-3">
                        <div class="form-group">
                          
                            <label for='key'>Notification Key</label>
                            <input type="text" class="form-control mt-2" name="key" value="{{isset($notifications->key) ? $notifications->key :''}}" placeholder="Enter Notification Name" readonly />
                        </div>
                    </div>
                    <div class=" col-md-4 mt-3">
                        <div class="form-group">
                            <label for='name'>Name</label>
                            <input type="text" class="form-control mt-2" name="name" value="{{isset($notifications->name) ? $notifications->name :''}}" placeholder="Enter Notification Subject" />
                        </div>
                    </div>
                    <div class=" col-md-4 mt-3">
                        <div class="form-group">
                            <label for='subject'>Subject</label>
                            <input type="text" class="form-control mt-2" name="subject" value="{{isset($notifications->subject) ? $notifications->subject :''}}" placeholder="Enter Notification Subject" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mt-5 is_mail_enabled">
                        <div class="form-group">
                            <label for='is_mail_enabled'>Mail Enabled</label>
                            <label class="switch form-switch" style="position: absolute;">
                                <input type="checkbox" value="1" name="is_mail_enabled" class="switch-input form-check-input" {{$notifications->is_mail_enabled == 1 ? 'checked' :''}}/>
                                <span class="slider round switch-slider"></span>
                            </label>
                        </div>
                    </div> 
                    <div class="col-12 col-md-4 mb-4 mt-5 is_wa_enabled">
                        <div class="form-group">
                            <label for='is_wa_enabled'>WhatsApp Enabled</label>
                            <label class="switch form-switch" style="position: absolute;">
                                <input type="checkbox" value="1" name="is_wa_enabled" class="switch-input form-check-input" {{$notifications->is_wa_enabled == 1 ? 'checked' :''}}/>
                                <span class="slider round switch-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mt-5 is_sms_enabled">
                        <div class="form-group">
                            <label for='is_sms_enabled'>SMS Enabled</label>
                            <label class="switch form-switch" style="position: absolute;">
                                <input type="checkbox" value="1" name="is_sms_enabled" class="switch-input form-check-input" {{$notifications->is_sms_enabled == 1 ? 'checked' :''}}/>
                                <span class="slider round switch-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mb-4 mt-3 mail_body">
                        <div class="form-group">
                            <label for='mail_body'>Mail Body</label>
                            <textarea id="mail_body" name="mail_body" rows="7" class="form-control" >{{isset($notifications->mail_body) ? $notifications->mail_body :''}}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mt-3 wa_body">
                        <div class="form-group">
                            <label for='wa_body'>WhatsApp Body</label>
                            <textarea class="form-control mt-2 wa_body" name="wa_body" rows="5" >{{isset($notifications->wa_body) ? $notifications->wa_body :''}}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-4 mt-3 sms_body">
                        <div class="form-group">
                            <label for='sms_body mb-3'>SMS Body</label>
                            <textarea class="form-control mt-2" name="sms_body" rows="5">{{isset($notifications->sms_body) ? $notifications->sms_body :''}}</textarea>
                        </div>
                    </div>
                </div><!--card-body-->
                <div class="card-footer">
                    <div class="row">
                        <div class="col text-right">
                            {{-- {{ form_submit(__('buttons.general.save')) }} --}}
                            <button type="button" class="btn btn-outline-success" id="save-draft">{{__('buttons.general.save')}}</button>
                        </div>
                        <!--row-->
                    </div>
                    <!--row-->
                </div>
            <!--card-footer-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
</div>
</form>
@php 
$variables = isset($notifications->variables) ? $notifications->variables :'';
$str = explode(',',$variables);
@endphp
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
     $(document).ready(function(){
        $('#save-draft').on('click',function(){
            var desc = CKEDITOR.instances['mail_body'].getData();
            var vari = <?php echo json_encode($str) ?>;
            var array = [];
            $.each(vari, function(key,val) {
               if(desc.indexOf(val) != -1){
                // $("#save-notification").submit();
                }else{
                    array.push(val)
                }
            });
            if(array.length > 0){
                 $.each(array, function(key,val) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: 'Need ' + val+ ' variable in email body!'
                    })
                });

            }else{
                $("#save-notification").submit();
            }
            
        })
      CKEDITOR.replace('mail_body');
      CKEDITOR.replace('wa_body');
      CKEDITOR.replace('sms_body');
      $("#save-notification1").submit(function(stay){
        var id = $('#id').val();
        cons
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.auth.notification.update')}}",
            data: $(this).serialize(),
            success: function (data) {
                if(data == 'success'){
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Notification successfully saved!'
                    })
                }else{
                     const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'warning',
                        title: 'Somthing went wronng!'
                    })
                }
               
            },
        });
        stay.preventDefault(); 
    });
    })
    
</script>
