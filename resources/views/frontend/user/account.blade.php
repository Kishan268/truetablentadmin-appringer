@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | My Account')

<style type="text/css">
    body{
        /* background-image: url('{{asset('img/frontend/account-profile.jpg')}}'); */
        /* background-size: cover; */
        /* background-position: center; */
        /* background-color: #fff; */
        color: #5a5a5a;
        background-color: #fff!important; 
    }
    .switch { 
        position : relative ;
        display : inline-block;
        width : 60px;
        height : 30px;
        background-color: #eee;
        border-radius: 40px;
    }
    .switch::after {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: white;
        top: 2px;
        left: 1px;
        transition: all 0.4s;
    }
    .checkbox:checked + .switch::after {
        left : 30px; 
    }
    .checkbox:checked + .switch {
        background-color: #7983ff;
    }
    .checkbox { 
        display : none;
    }
    #app{
        /* background-color: rgba(49, 49, 49, 0.6); */
        height: 100vh;
    }
    @media only screen and (max-width: 600px) {
        .accountContent{
            width: 100%!important;
        }
    }
       @media only screen and (max-width: 900px) {
        
        .align-self-center {
            margin-top: 30% !important;
            margin-left: 2%  !important;
        }   
        .card {
            margin-top: 30% !important;
            margin-left: 2%  !important;
        }  
    } 
    @media only screen and (max-width: 320px) {
        
        .card {
            margin-top: 30% !important;
        }
    }
   
    .align-self-center {
      margin-top: 6% !important;
    }   
</style>
@section('content')
    <div class="row p-3 mb-3 subNav">
        <div class="col-12 text-center">
            <h3 class="font-weight-bold mb-0">
                @lang('navs.frontend.user.account')
            </h3>
        </div>
    </div>
    <div class="row justify-content-center align-items-center mt-5 mx-auto accountContent" style="width: 60%;">
        <div class="col col-sm-11 align-self-center">
            <div class="card" style="border: none;">
                {{-- <div class="card-header text-center headerFontSizeWithoutCard mb-3" style="border:none;background-color:#fff;">
                    <strong>
                        @lang('navs.frontend.user.account')
                    </strong>
                </div> --}}

                <div class="card-body" style="min-height:70vh; border:none;">
                    <div role="tabpanel">

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active show pt-3" id="password" aria-labelledby="password-tab">
                                @include('frontend.user.account.tabs.change-password')
                            </div>
                        </div><!--tab content-->
                    </div><!--tab panel-->
                </div><!--card body-->
            </div><!-- card -->
        </div><!-- col-xs-12 -->
    </div><!-- row -->
@endsection
