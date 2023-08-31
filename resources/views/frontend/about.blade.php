@extends('frontend.layouts.app', ['container' => true])

@section('title', app_name() . ' | About Us')

<style type="text/css">
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
    }
    /* GLOBAL STYLES
    -------------------------------------------------- */
    /* Padding below the footer and lighter body text */

    body {
        /* padding-top: 3rem; */
        /* padding-bottom: 3rem; */
        background-color: #FFF!important; 
        color: #5a5a5a;
    }


    /* CUSTOMIZE THE CAROUSEL
    -------------------------------------------------- */

    /* Carousel base class */
    .carousel {
        margin-bottom: 4rem;
    }
    /* Since positioning the image, we need to help out the caption */
    .carousel-caption {
        bottom: 3rem;
        z-index: 10;
    }

    /* Declare heights because of positioning of img element */
    .carousel-item {
        height: 32rem;
    }
    .carousel-item > img {
        position: absolute;
        top: 0;
        left: 0;
        min-width: 100%;
        height: 32rem;
    }


    /* MARKETING CONTENT
    -------------------------------------------------- */

    /* Center align the text within the three columns below the carousel */
    .marketing .col-lg-4 {
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .marketing h2 {
        font-weight: 400;
    }
    .marketing .col-lg-4 p {
        margin-right: .75rem;
        margin-left: .75rem;
    }


    /* Featurettes
    ------------------------- */

    .featurette-divider {
        margin: 5rem 0; /* Space out the Bootstrap <hr> more */
    }

    /* Thin out the marketing headings */
    .featurette-heading {
        font-weight: 300;
        line-height: 1;
        letter-spacing: -.05rem;
    }


    /* RESPONSIVE CSS
    -------------------------------------------------- */

    @media (min-width: 40em) {
        /* Bump up size of carousel content */
        .carousel-caption p {
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .featurette-heading {
            font-size: 50px;
        }
    }

    @media (min-width: 62em) {
        .featurette-heading {
            margin-top: 7rem;
        }
    }

    .container-fluid{
        padding: 0!important;
    }
</style>
@section('content')

<main role="main" style="margin-bottom:2rem;">

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" --}}
                    {{-- preserveAspectRatio="xMidYMid slice" focusable="false" role="img"> --}}
                    {{-- <rect width="100%" height="100%" fill="#777" /></svg> --}}
                <img class="bd-placeholder-img" src='{{asset('img/frontend/About-1.jpg')}}'/>
                <div class="container">
                    <div class="carousel-caption text-left">
                        <h1>{{ env('APP_NAME') }}.</h1>
                        <p>The Talent Scout You Need At a Cost You’ll Love</p>
                        <p><a class="btn btn-lg btn-primary" href="{{route('frontend.auth.register', ['type' => 'corporate'])}}" role="button">Sign up</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                {{-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                    <rect width="100%" height="100%" fill="#777" /></svg> --}}
                <img class="bd-placeholder-img" src='{{asset('img/frontend/About-2.jpg')}}' />
                <div class="container">
                    <div class="carousel-caption">
                        <h1>{{ env('APP_NAME') }}.</h1>
                        <p>Your dream job at best locations in click of a button.</p>
                        <p><a class="btn btn-lg btn-primary" href="{{route('frontend.auth.login')}}" role="button">Learn more</a></p>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>


    <!-- Marketing messaging and featurettes
  ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

        <!-- Three columns of text below the carousel -->
        <div class="row text-center">
            <div class="col-lg-6">
                {{-- <svg class="bd-placeholder-img rounded-circle" width="140" height="140"
                    xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"
                    aria-label="Placeholder: 140x140">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#777" /><text x="50%" y="50%" fill="#777"
                        dy=".3em">140x140</text>
                </svg> --}}
                <img class="p-2 mb-3 bd-placeholder-img rounded-circle" src='{{asset('img/frontend/Vision.jpg')}}' width="200" height="200"/>
                <h2>Our Vision</h2>
                <p class="lead">Save up to 80% of company’s Total Cost of Hiring by presenting the best talent.</p>
                {{-- <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p> --}}
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-6">
                {{-- <svg class="bd-placeholder-img rounded-circle" width="140" height="140"
                    xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"
                    aria-label="Placeholder: 140x140">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#777" /><text x="50%" y="50%" fill="#777"
                        dy=".3em">140x140</text>
                </svg> --}}
                <img class="p-2 mb-3 bd-placeholder-img rounded-circle" src='{{asset('img/frontend/Mission.jpg')}}' width="200"
                    height="200" />
                <h2>Our Mission</h2>
                <p class="lead">Presenting relevant candidates at the least possible time, and with reduced effort resulting in a nominal cost.</p>
                {{-- <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p> --}}
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->


        <!-- START THE FEATURETTES -->

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading mb-4"><span class="text-muted">Who</span> We Are.</h2>
                <p class="lead">Experts in personnel recruiting, we are the next-gen job portal.</p>
                <p class="lead">Our three decades of combined experience providing solutions to a myriad of hiring challenges propelled us to invent a
                revolutionary assessment process to significantly lower the Total Cost of Hiring.</p>
                <p class="lead">We leverage a fusion of robust AI algorithms and SME evaluations to deliver the best pool of pre-assessed candidates to
                our clients.</p>
            </div>
            <div class="col-md-5">
                <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500"
                    height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                    focusable="false" role="img" aria-label="Placeholder: 500x500">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa"
                        dy=".3em">500x500</text>
                </svg>
            </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7 order-md-2">
                <h2 class="featurette-heading mb-4"><span class="text-muted">What</span> We Do.</h2>
                <p class="lead">We screen job seekers, sure. But we go deep. We look at their educational qualifications, their industry certifications
                and their work experience.</p>
                <p class="lead">And then we go further: We assess the candidates using AI (Artificial Intelligence) and HI (Human Intelligence) - with
                our industry-leading Assessment Engine and our stable of SMEs - to generate proprietary WorkProfiles™.</p>
                <p class="lead">No muss, no fuss - just the right candidates for the job.</p>
            </div>
            <div class="col-md-5 order-md-1">
                <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500"
                    height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                    focusable="false" role="img" aria-label="Placeholder: 500x500">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa"
                        dy=".3em">500x500</text>
                </svg>
            </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading mb-4">How it <span class="text-muted">Works.</span></h2>
                <p class="lead">
                    <ol>
                        <li class="lead">A job seeker sends us a resume. Once it’s uploaded to our database, we convert each resume into a special format to
                        identify the unique competencies of that particular candidate.</li>
                        <li class="lead">Job seeker undergoes Level 1 evaluation (AI assessment) of their competencies, and upon successfully clearing undergoes
                        a video interview with an established SME to assess hands-on knowledge of skills.</li>
                        <li class="lead">The AI Engine determines the relevance percentage of each candidate’s skills against each job description. The highest
                        matching WorkProfiles™ are presented to companies.</li>
                    </ol>
                </p>
            </div>
            <div class="col-md-5">
                <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500"
                    height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                    focusable="false" role="img" aria-label="Placeholder: 500x500">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa"
                        dy=".3em">500x500</text>
                </svg>
            </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
            <div class="col-md-7 order-md-2">
                <h2 class="featurette-heading mb-4">What’s in it for <span class="text-muted">Employers?</span></h2>
                <p class="lead">Whether your firm is established or a start-up, selecting the right talent is business critical.</p>
                <p class="lead">{{ env('APP_NAME') }} provides pre-screened and pre-assessed candidates (WorkProfiles™) for easing your first step of hiring.</p>
                <p class="lead">Our two-level process combines AI and HI (expert evaluation) to save you up to 80% of your traditional recruitment costs
                and effort.</p>
                <p class="lead">Most importantly, you find just the right person for the job.</p>
            </div>
            <div class="col-md-5 order-md-1">
                <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500"
                    height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false"
                    role="img" aria-label="Placeholder: 500x500">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">500x500</text>
                </svg>
            </div>
        </div>

        <hr class="featurette-divider">
        
        <div class="row featurette">
            <div class="col-md-7">
                <h2 class="featurette-heading mb-4">What’s in it for <span class="text-muted">Job Seekers?</span></h2>
                <p class="lead">Your resume won’t get lost in an ocean of applicants.</p>
                <p class="lead">Getting screened with {{ env('APP_NAME') }} reduces the initial screening interview rounds for all the jobs you apply.</p>
                <p class="lead">You’ll even get constructive feedback to improve your interview skills.</p>
                <p class="lead">Bottom line: We boost your resume to the top of the pyramid.</p>
            </div>
            <div class="col-md-5">
                <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500"
                    height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false"
                    role="img" aria-label="Placeholder: 500x500">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">500x500</text>
                </svg>
            </div>
        </div>

        <hr class="featurette-divider mb-5">

        <h2 class="featurette-heading mt-1 mb-4 text-center">Our <span class="text-muted">Team</span></h2>
        <div class="container marketing">
            <div class="row text-center">
                <div class="col-lg-6">
                    <center><div class="card" style="width: 18rem;">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg"
                            preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image
                                cap</text>
                        </svg>
                        <div class="card-body">
                            <h5 class="card-title">Ansuman Pattanaik, MBA</h5>
                            <p class="card-text">Co-Creator</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">20+ years of industry experience managing HR and recruitment functions</li>
                            <li class="list-group-item">Former HR head of a large and well known multinational company</li>
                        </ul>
                    </div></center>
                </div>
                <div class="col-lg-6">
                    <center><div class="card" style="width: 18rem;">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg"
                            preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Image cap">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image
                                cap</text>
                        </svg>
                        <div class="card-body">
                            <h5 class="card-title">Bipasha Tewary, MBA</h5>
                            <p class="card-text">Co-Creator</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Doctoral Candidate in Organizational Leadership</li>
                            <li class="list-group-item my-2">10+ years of global industry experience</li>
                        </ul>
                    </div></center>
                </div>
            </div>

        </div>

        <hr class="featurette-divider mb-5">

        <h2 class="featurette-heading mt-1 mb-4 text-center">Ansuman’s <span class="text-muted">Story</span></h2>
        <div class="container marketing">
            <div class="row text-center">
                <blockquote class="blockquote">
                    <p class="mb-0">
                        “Being involved in hiring for more than 20,000 positions, over a span of 2 decades, I know that a candidate may look good on paper but may not have the expertise to be productive at their job.
                        Hiring teams must either work through a thick pile of resumes to find one good fit, or they have to hire an expensive headhunter or placement firm. A third option, using a job portal, costs little but provides poor selection ratios—few good candidates for final selection.
                        {{ env('APP_NAME') }} is the brainchild where I wanted to offer a unique solution to combine the efficiency of headhunters and the cost effectiveness of job portals.”
                    </p>
                    <footer class="blockquote-footer"> <cite title="Source Title">Ansuman Pattanaik</cite></footer>
                </blockquote>
            </div>
        </div>
        <!-- /END THE FEATURETTES -->

    </div><!-- /.container -->

</main>

@endsection