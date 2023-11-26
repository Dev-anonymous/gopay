@extends('layouts.main')
@section('title', 'Dashboard')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h3 class="font-weight-bold">Dashboard</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-md-6 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('admin.web.trans') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line text-success fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        @foreach ($all->trans as $k => $v)
                                            <h5 class="font-weight-bold">
                                                {{ formatMontant($v, $k) }}
                                            </h5>
                                        @endforeach
                                        <p class="mb-0">Transactions API</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-6 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('admin.web.cashout') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-transfer text-warning fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        @foreach ($all->cashout as $k => $v)
                                            <h5 class="font-weight-bold">
                                                {{ formatMontant($v, $k) }}
                                            </h5>
                                        @endforeach
                                        <p class="mb-0">Cash out</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-6 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('admin.web.trans') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-right-left text-danger fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3>{{ $all->nb_trans }}</h3>
                                        <p class="mb-0">Transactions</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-6 col-12 mb-4">
                    <div class="card">
                        <a href="{{ route('admin.web.merchent') }}" class="text-dark">
                            <div class="card-body font-weight-bold" style="height: 130px;">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <i class="fas fa-users-gear text-dark fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3>{{ $marchands }}</h3>
                                        <p class="mb-0">Marchands</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body font-weight-bold" style="height: 130px;">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-coins text-secondary fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    @foreach ($all->solde_marchand as $k => $v)
                                        <h5 class="font-weight-bold">
                                            {{ formatMontant($v, $k) }}
                                        </h5>
                                    @endforeach
                                    <p class="mb-0">Solde marchands
                                        <small class="small font-italic">
                                            (-{{ COMMISSION * 100 }}% )
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body font-weight-bold" style="height: 130px;">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-coins text-info fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    @foreach ($all->solde as $k => $v)
                                        <h5 class="font-weight-bold">
                                            {{ formatMontant($v, $k) }}
                                        </h5>
                                    @endforeach
                                    <p class="mb-0">Solde {{ config('app.name') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <x-footer />
@endsection

@section('js-code')
    <script>
        $(function() {})
    </script>
@endsection
