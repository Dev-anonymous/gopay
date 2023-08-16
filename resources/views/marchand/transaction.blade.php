@extends('layouts.main')
@section('title', 'Transactions')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h3 class="font-weight-bold">   TRANSACTIONS     ({{ count($trans) }})</h3>
                        <div>
                            <select name="" id="Type" class="form-control" disabled>
                                <option value="">VALIDEES</option>
                                <option value="">EN ATTENTE</option>
                                <option value="">ECHOUEES</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata
                            class="table table-sm table-condensed table-hover table-striped text-nowrap font-weight-bold">
                            <thead class="table-dark">
                                <th></th>
                                <th>TRANS. ID</th>
                                <th>MONTANT</th>
                                <th>TYPE</th>
                                <th>SOURCE</th>
                                <th>DATE</th>
                            </thead>
                            <tbody>
                                @foreach ($trans as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->trans_id }}</td>
                                        <td>{{ $v->montant }}</td>
                                        <td>{{ $v->type }}</td>
                                        <td>{{ $v->source }}</td>
                                        <td>{{ $v->date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
@endsection

@section('js-code')
    <script>
        $(function() {

        })
    </script>
@endsection
