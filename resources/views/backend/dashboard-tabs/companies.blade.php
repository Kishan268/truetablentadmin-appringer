<div class="col-md-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row res">
                <div class="col-1">
                    <img class="horn-icon" src="{{ asset('assets/images/others/round-horn-icon.png') }}" alt="vector">

                </div>
                <div class="col-5 title">
                    <h4>Companies</h4>
                    <div class="description">
                        <img src="{{ asset('assets/images/others/users.png') }}">
                        <p>{{ $data['active_companies'] }} Total Active Companies</p>
                    </div>
                </div>
                <div class="col-3 c-name">
                    <div class="company-count">
                        <p class="tag-label">NEW COMPANIES</p>
                        <div class="tag-data">
                            <p>{{ $data['new_companies'] }}</p>
                            @if ($data['previous_range_new_companies'] > $data['new_companies'])
                                <img class="indicator-icon" src="{{ asset('assets/images/others/down.png') }}">
                            @else
                                <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                            @endif
                        </div>
                    </div>
                    <div class="company-count">
                        <p class="tag-label">TOTAL COMPANIES</p>
                        <div class="tag-data">
                            <p>{{ $data['total_companies'] }}</p>
                            <img class="indicator-icon" src="{{ asset('assets/images/others/up.png') }}">
                        </div>
                    </div>
                </div>
                <div class="col-3 c-name">
                    <div class="company-count">
                        <p class="tag-label">PRO COMPANIES</p>
                        <div class="tag-data">
                            <p>NA</p>

                        </div>
                    </div>
                    <div class="company-count">
                        <p class="tag-label">ACTIVE COMPANIES</p>
                        <div class="tag-data">
                            <p>{{ $data['active_companies'] }}</p>
                            @if ($data['previous_range_active_companies'] > $data['active_companies'])
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
                <h6 class="card-title">Company Growth</h6>
                <canvas id="chartjsGroupedBar"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Companies By Industries</h6>
                <canvas id="chartjsDoughnut"></canvas>
            </div>
            <div class="row mb-3">
                @foreach ($data['companies_by_industries'] as $key => $company)
                    <div class="col-6 d-flex industry-div">
                        <div>
                            @php
                                $backgroundColors = ['#553AFE', '#01C0F6', '#DFDC27', '#033270', '#D4A276', '#FF8000', '#5BC0BE', '#F26A4F', '#FA699D', '#20C9AC'];
                            @endphp
                            <span style="background-color: {{ @$backgroundColors[$key] }};"
                                class="mt-2">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>
                        <div>
                            <label
                                class="d-flex align-items-center justify-content-end tx-12 text-uppercase fw-bolder">{{ $company->name }}</label>
                            <h5 class="fw-bolder mb-0">{{ $company->company_count }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var companies_by_industries_graph = [];
    companies_by_industries_graph['labels'] = @json($data['companies_by_industries_graph']['labels']);
    companies_by_industries_graph['data'] = @json($data['companies_by_industries_graph']['data']);
    var graph1_data = @json($data['graph1_data']);

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

    if ($('#chartjsDoughnut').length) {
        new Chart($('#chartjsDoughnut'), {
            type: 'doughnut',
            data: {
                labels: companies_by_industries_graph['labels'],
                datasets: [{
                    label: "COMPANIES BY INDUSTRIES",
                    backgroundColor: ['#553AFE', '#01C0F6', '#DFDC27', '#033270', '#D4A276', '#FF8000',
                        '#5BC0BE', '#F26A4F', '#FA699D', '#20C9AC'
                    ],
                    borderColor: colors.cardBg,
                    data: companies_by_industries_graph['data'],
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
    if ($('#chartjsGroupedBar').length) {
        new Chart($('#chartjsGroupedBar'), {
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
</script>
