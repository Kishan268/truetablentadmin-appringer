<div class="tab-pane fade show" role="tabpanel" aria-labelledby="companies-line-tab">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row res">
                    <div class="col-1">
                        <img class="horn-icon" src="{{ asset('assets/images/others/round-horn-icon.png') }}"
                            alt="vector">

                    </div>
                    <div class="col-5 title">
                        <h4>Candidates</h4>
                        <div class="description">
                            <img src="{{ asset('assets/images/others/users.png') }}">
                            <p>{{ $data['active_candidates'] }} Total Active Candidates</p>
                        </div>
                    </div>
                    <div class="col-3  c-name">
                        <div class="company-count">
                            <p class="tag-label">NEW CANDIDATES</p>
                            <div class="tag-data">
                                <p>{{ $data['new_candidates'] }}</p>
                                @if (
                                    $data['previous_range_new_candidates'] > $data['new_candidates'] ||
                                        $data['previous_range_new_candidates'] == $data['new_candidates']
                                )
                                    <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                @else
                                    <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                @endif
                            </div>
                        </div>
                        <div class="company-count">
                            <p class="tag-label">GENDER DIVERSITY RATIO</p>
                            <div class="tag-data">
                                <?php
                                $male = 0;
                                $female = 0;
                                for ($i = 0; $i < 3; $i++) {
                                    if (isset($data['candidates_by_industries_graph']['labels'][$i])) {
                                        if ($data['candidates_by_industries_graph']['labels'][$i] == 'Male') {
                                            $male = isset($data['candidates_by_industries_graph']['data'][$i]) === true ? @$data['candidates_by_industries_graph']['data'][$i] : 0;
                                        } elseif ($data['candidates_by_industries_graph']['labels'][$i] == 'Female') {
                                            $female = isset($data['candidates_by_industries_graph']['data'][$i]) === true ? $data['candidates_by_industries_graph']['data'][$i] : 0;
                                        }
                                    }
                                }
                                $sum = (int) $male + (int) $female;
                                ?>
                                <p>{{ $male . ':' . $female }} </p>

                                {{-- <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}"> --}}
                                @if (@$data['previous_range_new_candidates_age_sum'] > $sum || @$data['previous_range_new_candidates_age_sum'] == $sum)
                                    <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                                @else
                                    <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-3  c-name">
                        <div class="company-count">
                            <p class="tag-label">HIRED CANDIDATES</p>
                            <div class="tag-data">
                                <p>NA</p>
                            </div>
                        </div>
                        <div class="company-count">
                            <p class="tag-label">INCOMPLETE PROFILES</p>
                            <div class="tag-data">
                                <p>{{ $data['incompleteProfileCount'] }}</p>
                                @if ($data['previousIncompleteProfileCount'] > $data['incompleteProfileCount'])
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
    </div>

    <div class="row">
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Candidates Growth</h6>
                    <canvas id="chartjsGroupedBarCandidates"></canvas>
                </div>
            </div>
        </div>


        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">MALE-FEMALE RATIO</h6>
                    <canvas id="chartjsDoughnutCandidates"></canvas>
                </div>
                <div class="row mb-3">
                    @foreach ($data['all_candidates'] as $key => $candicate)
                        @if ($candicate->gender)
                            <div class="col-6 d-flex industry-div">
                                <div>
                                    @php
                                        $backgroundColors = ['#033270', '#553AFE', '#14BC9A'];
                                    @endphp
                                    <span style="background-color: {{ @$backgroundColors[$key] }};"
                                        class="mt-2">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                </div>
                                <div>
                                    <label
                                        class="d-flex align-items-center justify-content-end tx-12 text-uppercase fw-bolder">{{ $candicate->gender }}</label>
                                    <h5 class="fw-bolder mb-0">{{ $candicate->sum }}</h5>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Candidates by Age</h6>
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var candidates_by_industries_graph = [];
    candidates_by_industries_graph['labels'] = @json($data['candidates_by_industries_graph']['labels']);
    candidates_by_industries_graph['data'] = @json($data['candidates_by_industries_graph']['data']);
    var graph1_data = @json($data['graph1_data']);
    var datasetdataset = @json($data['graph2_data']);

    $(function() {
        'use strict';


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




        // New Customers Chart
        // New Customers Chart
        if ($('#ageChart').length) {
            new Chart($('#ageChart'), {
                type: 'bar',
                data: {
                    labels: datasetdataset.labels,
                    datasets: datasetdataset.datasets
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                color: colors.bodyColor,
                                font: {
                                    size: '20px',
                                    family: fontFamily
                                }
                            }
                        },
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.5
                        }],
                        x: {
                            display: false,
                            grid: {
                                display: true,
                                color: colors.gridBorder,
                                borderColor: colors.gridBorder,
                            },
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: true,
                                color: colors.gridBorder,
                                borderColor: colors.gridBorder,
                            },
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    responsive: true,
                    // borderRadius: 20,
                    // barThickness: 5,
                },
                responsive: true,
                cutout: "90%"
            });

            // new ApexCharts(document.querySelector("#customersChart"),options1).render();
        }
        // Doughnut Chart
        if ($('#customersChart').length) {
            new Chart($('#customersChart'), {
                type: 'doughnut',
                data: {
                    labels: candidates_by_industries_graph['labels'],
                    datasets: [{
                        label: "MALE-FEMALE RATIO",
                        backgroundColor: ['#033270', '#553AFE', '#14BC9A'],
                        borderColor: colors.cardBg,
                        data: candidates_by_industries_graph['data'],
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

        // Doughnut Chart
        if ($('#chartjsDoughnutCandidates').length) {
            new Chart($('#chartjsDoughnutCandidates'), {
                type: 'doughnut',
                data: {
                    labels: candidates_by_industries_graph['labels'],
                    datasets: [{
                        label: "MALE-FEMALE RATIO",
                        backgroundColor: ['#033270', '#553AFE', '#14BC9A'],
                        borderColor: colors.cardBg,
                        data: candidates_by_industries_graph['data'],
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




        // Grouped Bar Chart
        if ($('#chartjsGroupedBarCandidates').length) {
            new Chart($('#chartjsGroupedBarCandidates'), {
                type: 'bar',
                data: {
                    labels: graph1_data.labels,
                    datasets: graph1_data.datasets
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                color: colors.bodyColor,
                                font: {
                                    size: '13px',
                                    family: fontFamily
                                }
                            }
                        },
                    },
                    scales: {
                        x: {
                            display: false,
                            grid: {
                                display: true,
                                color: colors.gridBorder,
                                borderColor: colors.gridBorder,
                            },
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: true,
                                color: colors.gridBorder,
                                borderColor: colors.gridBorder,
                            },
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    responsive: true,
                    borderRadius: 10,
                    barThickness: 5,
                }
            });
        }





    });
</script>
