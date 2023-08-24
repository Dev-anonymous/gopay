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
                        <h3 class="font-weight-bold"> TRANSACTIONS (<span nb></span>)</h3>
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
                                <th>MARCHAND</th>
                                <th>NUMERO. COMPTE</th>
                                <th>TRANS. ID</th>
                                <th class="text-center">MONTANT</th>
                                <th class="text-center">SOURCE</th>
                                <th>API DATA</th>
                                <th class="text-right">DATE</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
@endsection

@section('js-code')
    @include('files.datatable-js')
    <script src="{{ asset('js/swal/swal.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/swal/swal/swal.min.css') }}">
    <script>
        $(function() {
            (new DataTable('[tdata]', {
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.api.trans', ['datatable' => '']) }}&source=E-PAY",
                    beforeSend: function() {
                        $('[tdata]').closest('div').LoadingOverlay("show", {
                            maxSize: 50
                        });
                    },
                    complete: function() {
                        $('[tdata]').closest('div').LoadingOverlay("hide");
                    },
                    error: function(resp) {
                        $('[onerror]').slideDown();
                    }
                },
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'numero_compte',
                        name: 'numero_compte',
                        class: 'text-center'
                    },
                    {
                        data: 'trans_id',
                        name: 'trans_id',
                        searchable: false,
                        orderable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'montant',
                        name: 'montant',
                        class: 'text-center text-nowrap'
                    },
                    {
                        data: 'source',
                        name: 'source',
                        class: 'text-center'
                    },
                    {
                        data: 'data',
                        name: 'data',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'date',
                        name: 'date',
                        class: 'text-right'
                    },
                ]
            })).on('xhr.dt',
                function(e, settings, data, xhr) {
                    $('span[nb]').html(data.recordsTotal);
                });
        })
    </script>
@endsection
