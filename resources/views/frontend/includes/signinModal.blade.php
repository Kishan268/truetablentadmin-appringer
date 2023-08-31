<!-- Sign Up Window Code -->
<div class="modal fade" id="signin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="myModalLabel1">
            <div class="modal-body">
                <div class="text-center"><img src="{{ asset('img/logo.png') }}" alt="" class="img-responsive">
                </div>

                <!-- Nav tabs -->
                {{-- <ul class="nav nav-tabs nav-advance theme-bg" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" href="#employer" role="tab">
                        <i class="ti-user"></i> Employer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#candidate" role="tab">
                        <i class="ti-user"></i> Candidate</a>
                    </li>
                </ul>
                <!-- Nav tabs --> --}}

                <!-- Tab panels -->
                <div class="tab-content">

                    <!-- Employer Panel 1-->
                    <div class="tab-pane fade in show active" id="employer" role="tabpanel">
                        <form method="POST" action="{{ route('frontend.auth.login.post') }}">
                            @csrf
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" name="email" class="form-control" placeholder="User Name">
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="*********">
                            </div>

                            <div class="form-group">
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="4">
                                    <label for="4"></label>Remember me
                                </span>
                                <a href="#" title="Forget" class="fl-right">Forgot Password?</a>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn theme-btn full-width btn-m">LogIn </button>
                            </div>

                        </form>

                        <div class="log-option"><span>OR</span></div>

                        <div class="row mrg-bot-20">
                            <div class="col-md-6">
                                <a href="{{ route('frontend.auth.register', ['type' => 'candidate']) }}" title=""
                                    class="fb-log-btn log-btn"> Create Candidate Account</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('frontend.auth.register', ['type' => 'corporate']) }}" title=""
                                    class="gplus-log-btn log-btn"> Create Corporate Account+</a>
                            </div>
                        </div>

                    </div>
                    <!--/.Panel 1-->

                    <!-- Candidate Panel 2-->
                    <div class="tab-pane fade" id="candidate" role="tabpanel">
                        <form method="POST" action="{{ route('frontend.auth.login.post') }}">

                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" class="form-control" placeholder="User Name">
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="*********">
                            </div>

                            <div class="form-group">
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="44">
                                    <label for="44"></label>Remember me
                                </span>
                                <a href="#" title="Forget" class="fl-right">Forgot Password?</a>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn theme-btn full-width btn-m">LogIn </button>
                            </div>

                        </form>

                        <div class="log-option"><span>OR</span></div>

                        <div class="row mrg-bot-20">
                            <div class="col-md-6">
                                <a href="#" title="" class="fb-log-btn log-btn"> Create Candidate
                                    Account</a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" title="" class="gplus-log-btn log-btn"> Create Corporate
                                    Account</a>
                            </div>
                        </div>

                    </div>
                    <!--/.Panel 2-->

                </div>
                <!-- Tab panels -->
            </div>
        </div>
    </div>
</div>
<!-- End Sign Up Window -->
