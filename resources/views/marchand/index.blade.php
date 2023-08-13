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
                        <h3 class="font-weight-bold">Dashboard</h3>
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
                                    @foreach ($solde as $k => $v)
                                        <h5 class="font-weight-bold">
                                            {{ formatMontant($v, $k) }}
                                        </h5>
                                    @endforeach
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
                                        <h3 class="font-weight-bold">{{ $transfert }}</h3>
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
                                        <h3 class="font-weight-bold">{{ $trans }}</h3>
                                        <p class="mb-0">Transations</p>
                                    </div>
                                </div>
                            </div>
                        </a>
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
