@extends('layouts.main')
@section('title', 'Dashboard Merchant')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold">Dashboard</h6>
                        <div class="">
                            <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                                <i class="fa fa-refresh mr-1"></i>
                                Bureau de Change
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body font-weight-bold" style="height: 130px;">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-coins text-secondary fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <span class="fas fa-2x fa-spinner fa-spin" solde></span>
                                    <p class="mb-0">Solde
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('marchand.web.cashout') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-transfer text-warning fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <span class="fas fa-2x fa-spinner fa-spin" transfert></span>
                                        <p class="mb-0">Transferts fonds</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('marchand.web.trans') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-right-left text-danger fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <span class="fas fa-2x fa-spinner fa-spin" trans></span>
                                        <p class="mb-0">Transactions</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="w-100 text-center">
                            <i class="fa fa-spinner fa-spin" ldr></i>
                        </div>
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="font-weight-bold">Statistique de transactions</h5>
                            <div class="d">
                                <select class="form-control" id="year">
                                    @foreach ($years as $y)
                                        <option>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="transchart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    <div class="modal fade" id="mdladd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Echange de monnaie</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <div class="modal-body">
                    <div class="bg-white rounded shadow-lg p-5">
                        <p>
                            Vous pouvez échanger facilement et instantanément votre argent en CDF vers USD et vice versa
                            à un cout de {{ TAUX_CHANGE * 100 }}% du montant par échange.
                        </p>
                        <hr>
                        <div class="form-outline mb-2 input-group flex-nowrap">
                            <input required type="number" step="0.01" name="amount" min="0.5" class="form-control"
                                value="500" />
                            <label class="form-label" for="form1Example1">Je veux échanger</label>
                            <span class="input-group-text" id="addon-wrapping">
                                <select class='form-control' name="devise">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </span>
                            <span class="input-group-text" id="addon-wrapping">
                                <span labto></span>
                            </span>
                        </div>
                        <div id="rep"></div>
                        @if ($taux)
                            <div class="p-1 rounded-lg" style="background: rgba(0, 0, 0, .075)">
                                <p class="m-0">Taux</p>
                                <hr class="m-0">
                                <b>{{ formatMontant($taux->usd_cdf) }}</b>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="" div0>
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-dark btn-sm" bok>
                            <i class="fa fa-refresh"></i>
                            Echanger
                        </button>
                    </div>
                    <div style="display: none" div1 class="w-100">
                        <div class="p-3">
                            <p class="font-weight-bold">Voulez-vous effectuer cette opération d'échange ?</p>
                            <b id="rep1"></b>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="">
                                <button type="button" bnon class="btn btn-seconday btn-sm mr-2">NON</button>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-danger btn-sm" bchange>
                                    <i class="fa fa-refresh"></i>
                                    OUI ECHANGER
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js-code')
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
    <script>
        $(function() {
            var options2 = {
                series: [],
                chart: {
                    height: 350,
                    animations: {
                        speed: 500,
                        enabled: false,
                    },
                    dropShadow: {
                        enabled: true,
                        enabledOnSeries: undefined,
                        top: 8,
                        left: 0,
                        blur: 3,
                        color: '#000',
                        opacity: 0.1
                    },
                    zoom: {
                        enabled: false,
                    }
                },
                colors: ["rgba(119, 119, 142, 0.15)", "rgba(35, 183, 229, 0.5)", "rgba(20, 190, 12, 0.75)",
                    "rgb(132, 90, 223)",
                    "#ffa900", '#f93154',

                ],
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                },
                stroke: {
                    curve: 'smooth',
                    width: [2, 2, 2, 2],
                    dashArray: [0, 5, 0],
                },
                xaxis: {
                    axisTicks: {
                        show: false,
                    },
                },
                tooltip: {
                    y: [{
                        formatter: function(e) {
                            return void 0 !== e ? e.toFixed(3) : e
                        }
                    }, ]
                },
                legend: {
                    show: true,
                },
                markers: {
                    hover: {
                        sizeOffset: 5
                    }
                }
            };
            var transchart = new ApexCharts(document.querySelector("#transchart"), options2);
            transchart.render();

            ldr = true;

            function getdata(interval = false) {
                if (ldr) {
                    ldr = false;
                    $('[ldr]').removeClass().addClass('fa fa-spinner fa-spin');
                }
                $.ajax({
                    'url': '{{ route('dash.index') }}',
                    data: {
                        'year': $('#year').val()
                    },
                    success: function(data) {
                        var transchartseries = data.transchart;
                        transchart.updateSeries(transchartseries);
                        $('[transfert]').removeClass().addClass('font-weight-bold h3').html(data
                            .transfert);
                        $('[trans]').removeClass().addClass('font-weight-bold h3').html(data.trans);
                        $('[solde]').removeClass().addClass('font-weight-bold h3').html(data.solde.join(
                            '<br>'));
                    },
                    error: function(res) {

                    }
                }).always(function() {
                    if (interval) {
                        setTimeout(() => {
                            getdata(true);
                        }, 5000);
                    }
                    $('[ldr]').removeClass();
                })
            }
            getdata(true);

            $('#year').change(function() {
                ldr = true;
                getdata();
            });

            function lab() {
                var v = $('[name=devise]').val();
                if (v == 'CDF') {
                    $('[labto]').html('Vers USD');
                } else if (v == 'USD') {
                    $('[labto]').html('Vers CDF');
                }
            }

            $('[name=devise]').change(function() {
                lab();
                getval();
            });
            lab();

            $('[name=amount]').on('keyup change', function() {
                getval();
            });

            function getval() {
                var rep = $('#rep');
                rep.removeClass().addClass('text-center').html(
                    `<i class="text-center spinner-border spinner-border-sm"></i>`);

                $.ajax({
                    url: '{{ route('marchand.api.willbe') }}',
                    type: 'POST',
                    data: {
                        'amount': $('[name=amount]').val(),
                        'devise': $('[name=devise]').val(),
                    },
                    timeout: 20000,
                    success: function(res) {
                        rep.html(res.message).removeClass().addClass(
                            'text-cente font-weight-bold text-' + (res
                                .success ?
                                'success' : 'danger'));
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        rep.removeClass().addClass('text-center text-danger').html(mess);
                    }
                })
            }

            $('[bok]').click(function() {
                $('[div0]').slideUp();
                $('[div1]').slideDown();
                $('[bchange]').show();
                $('#rep1').html('');
            });
            $('[bnon]').click(function() {
                $('[div0]').slideDown();
                $('[div1]').slideUp();
            });

            $('[bchange]').click(function() {
                var btn = $(this);
                var i = btn.find('i');
                var cl = i.attr('class');
                i.removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                var rep = $('#rep1');
                rep.html('');

                $.ajax({
                    url: '{{ route('marchand.api.exchange') }}',
                    type: 'POST',
                    data: {
                        'amount': $('[name=amount]').val(),
                        'devise': $('[name=devise]').val(),
                    },
                    timeout: 20000,
                    success: function(res) {
                        rep.html(res.message).removeClass().addClass(
                            'text-cente font-weight-bold text-' + (res
                                .success ?
                                'success' : 'danger'));

                        if (res.success) {
                            getdata();
                            btn.hide();
                            setTimeout(() => {
                                $('[bnon]').click();
                            }, 10000);
                        }
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        rep.removeClass().addClass('text-center text-danger').html(mess);
                    }
                }).always(function() {
                    i.removeClass().addClass(cl);
                    btn.attr('disabled', false);
                });
            });

        });
    </script>
@endsection
