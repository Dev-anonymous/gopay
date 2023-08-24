@extends('layouts.main')
@section('title', 'Feedback')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h3 class="font-weight-bold">FEEDBACKS (<span nb></span>)</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-sm table-condensed table-hover table-striped">
                            <thead class="table-dark">
                                <th></th>
                                <th>UTILISATEUR</th>
                                <th>CONTACT</th>
                                <th>SUJET</th>
                                <th>MESSAGE</th>
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
            var datatableOb = (new DataTable('[tdata]', {
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
                    url: "{{ route('admin.api.feedback', ['datatable' => '']) }}",
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
                        data: 'nom',
                        name: 'nom'
                    },
                    {
                        data: 'contact',
                        name: 'contact',
                    },
                    {
                        data: 'sujet',
                        name: 'sujet',
                    },
                    {
                        data: 'message',
                        name: 'message',
                    },
                    {
                        data: 'date',
                        name: 'date',
                        class: 'text-right'
                    }
                ]
            })).on('xhr.dt',
                function(e, settings, data, xhr) {
                    $('span[nb]').html(data.recordsTotal);
                });
        })
    </script>
@endsection
