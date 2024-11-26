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
                        <h6 class="font-weight-bold"> TRANSACTIONS (<span nb></span>)</h6>
                        <div>
                            {{-- <select name="" id="Type" class="form-control" disabled>
                                <option value="">VALIDEES</option>
                                <option value="">EN ATTENTE</option>
                                <option value="">ECHOUEES</option>
                            </select> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-sm table-condensed table-hover table-striped font-weight-bold"
                            style="width: 100%">
                            <thead class="table-dark">
                                <th></th>
                                <th>TRANS. ID</th>
                                <th class="text-center">MONTANT</th>
                                <th class="text-center">SOURCE</th>
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
                    url: "{{ route('marchand.api.trans', ['datatable' => '']) }}",
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
                        data: 'trans_id',
                        name: 'trans_id'
                    },
                    {
                        data: 'montant',
                        name: 'montant',
                        class: 'text-nowrap text-center'
                    },
                    {
                        data: 'source',
                        name: 'source',
                        class: 'text-center'
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
