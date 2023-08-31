<div class="tab-content mt-3" id="lineTabContent">
    <div class="tab-pane fade show active" id="companies" role="tabpanel" aria-labelledby="companies-line-tab">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row res">
                        <div class="col-1">
                            <img class="horn-icon" src="{{ asset('assets/images/others/round-horn-icon.png') }}"
                                alt="vector">

                        </div>
                        <div class="col-5 title">
                            <h4>Jobs and Gigs</h4>
                            <div class="description">
                                <img src="{{ asset('assets/images/others/users.png') }}">
                                <p>{{ $data['jobs_count'] }} Total Jobs & {{ $data['active_gigs_count'] }} Total Gigs
                                </p>
                            </div>
                        </div>
                        <div class="col-3  c-name">
                            <div class="company-count">
                                <p class="tag-label">ACTIVE JOBS</p>
                                <div class="tag-data">
                                    <p>{{ $data['active_jobs_count'] }}</p>
                                    @if ($data['previous_active_jobs_count'] > $data['active_jobs_count'])
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                    @else
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                    @endif
                                </div>
                            </div>
                            <div class="company-count">
                                <p class="tag-label">CLOSED JOBS</p>
                                <div class="tag-data">
                                    <p>{{ $data['closed_jobs_count'] }}</p>
                                    @if ($data['previous_closed_jobs_count'] > $data['closed_jobs_count'])
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                    @else
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-3  c-name">
                            <div class="company-count">
                                <p class="tag-label">ACTIVE GIGS</p>
                                <div class="tag-data">
                                    <p>{{ $data['active_gigs_count'] }}</p>
                                    @if ($data['previous_active_gigs_count'] > $data['active_gigs_count'])
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                    @else
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                    @endif

                                </div>
                            </div>
                            <div class="company-count">
                                <p class="tag-label">CLOSED GIGS</p>
                                <div class="tag-data">
                                    <p>{{ $data['closed_gigs_count'] }}</p>
                                    @if ($data['previous_closed_gigs_count'] > $data['closed_gigs_count'])
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                    @else
                                        <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-xl-12 stretch-card">
                    <div class="row flex-grow-1">
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body featured-card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0 featured-title">FEATURED JOBS- HOME</h6>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-9 col-md-12 col-xl-9">
                                            <h3 class="mb-2 featured-count">{{ $data['featured_jobs_count'] }}</h3>

                                        </div>
                                        <div class="col-3 col-md-12 col-xl-3">
                                            @if ($data['previous_featured_jobs_counts'] > $data['featured_jobs_count'])
                                                <img class="indicator-icon right"
                                                    src="{{ asset('assets/images/others/down.png') }}">
                                            @else
                                                <img class="indicator-icon right"
                                                    src="{{ asset('assets/images/others/up.png') }}">
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body featured-card-body">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <h6 class="card-title mb-0 featured-title">FEATURED GIGS- HOME</h6>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-9 col-md-12 col-xl-9">
                                            <h3 class="mb-2 featured-count">{{ $data['featured_gigs_count'] }}</h3>

                                        </div>
                                        <div class="col-3 col-md-12 col-xl-3">
                                            @if ($data['previous_featured_gigs_counts'] > $data['featured_gigs_count'])
                                                <img class="indicator-icon right"
                                                    src="{{ asset('assets/images/others/down.png') }}">
                                            @else
                                                <img class="indicator-icon right"
                                                    src="{{ asset('assets/images/others/up.png') }}">
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <h6 class="card-title">Jobs by categories</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 grid-margin stretch-card center">
                            <canvas id="jobsDoughnut"></canvas>
                        </div>
                        @if (count($data['jobs_by_industries']) > 0)
                            <div class="col-xl-4 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="pt-0">Category</th>
                                                <th class="pt-0">ACTIVE</th>
                                                <th class="pt-0">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $backgroundColors = ['#553AFE', '#01C0F6', '#DFDC27', '#033270', '#D4A276', '#FF8000', '#5BC0BE', '#F26A4F', '#FA699D', '#20C9AC'];
                                            @endphp
                                            @if (isset($data['jobs_by_industries']) && count($data['jobs_by_industries']) > 0)
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if (isset($data['jobs_by_industries'][$i]))
                                                        <tr>
                                                            <td><span
                                                                    style="background-color: {{ @$backgroundColors[$i] }};"
                                                                    class="mt-2">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;{{ $data['jobs_by_industries'][$i]->name }}
                                                            </td>
                                                            <td>{{ $data['jobs_by_industries'][$i]->job_count }}</td>
                                                            <td>{{ App\Helpers\SiteHelper::getIndustryJobsCount($data['jobs_by_industries'][$i]->id) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if (count($data['jobs_by_industries']) > 5)
                            <div class="col-xl-4 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="pt-0">Category</th>
                                                <th class="pt-0">ACTIVE</th>
                                                <th class="pt-0">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($data['jobs_by_industries']) && count($data['jobs_by_industries']) > 0)
                                                @for ($i = 5; $i < count($data['jobs_by_industries']); $i++)
                                                    @if (isset($data['jobs_by_industries'][$i]))
                                                        <tr>
                                                            <td><span
                                                                    style="background-color: {{ @$backgroundColors[$i] }};"
                                                                    class="mt-2">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;{{ $data['jobs_by_industries'][$i]->name }}
                                                            </td>
                                                            <td>{{ $data['jobs_by_industries'][$i]->job_count }}</td>
                                                            <td>{{ App\Helpers\SiteHelper::getIndustryJobsCount($data['jobs_by_industries'][$i]->id) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var jobs_by_industries_graph = [];
        jobs_by_industries_graph['labels'] = @json($data['jobs_by_industries_graph']['labels']);
        jobs_by_industries_graph['data'] = @json($data['jobs_by_industries_graph']['data']);

        var colors = {
            primary: "#14BC9A",
            secondary: "#7987a1",
            success: "#14BC9A",
            info: "#66d1d1",
            warning: "#fbbc06",
            danger: "#ff3366",
            light: "#e9ecef",
            dark: "#060c17",
            muted: "#7987a1",
            gridBorder: "rgba(77, 138, 240, .15)",
            bodyColor: "#000",
            cardBg: "#fff"
        }

        var fontFamily = "'Roboto', Helvetica, sans-serif"


        if ($('#jobsDoughnut').length) {
            new Chart($('#jobsDoughnut'), {
                type: 'doughnut',
                data: {
                    labels: jobs_by_industries_graph['labels'],
                    datasets: [{
                        label: "JOBS BY INDUSTRIES",
                        backgroundColor: ['#553AFE', '#01C0F6', '#DFDC27', '#033270', '#D4A276', '#FF8000',
                            '#5BC0BE', '#F26A4F', '#FA699D', '#20C9AC'
                        ],
                        borderColor: colors.cardBg,
                        data: jobs_by_industries_graph['data'],
                    }]
                },
                options: {
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: false,
                            labels: {
                                color: colors.bodyColor,
                                font: {
                                    size: '13px',
                                    family: fontFamily
                                }
                            }
                        },
                    },
                    responsive: true,
                    cutout: "90%"
                }

            });
        }
    </script>
