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
                        <h3 class="font-weight-bold" title></h3>
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
                                <th>
                                    <div loader class="spinner-border spinner-border-sm"></div>
                                </th>
                                <th>MARCHAND</th>
                                <th>NUMERO. COMPTE</th>
                                <th>TRANS. ID</th>
                                <th>MONTANT</th>
                                <th>TYPE</th>
                                <th>SOURCE</th>
                                <th>API DATA</th>
                                <th>DATE</th>
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
    <script>
        $(function() {
            var tdata = $('[tdata]');
            $('[loader]').fadeIn();

            function getdata() {
                $.ajax({
                    url: '{{ route('admin.api.trans') }}',
                    timeout: 20000,
                    success: function(res) {
                        $('[title]').html(res.message);
                        var str = '';

                        $(res.data).each(function(i, e) {
                            var data = '';
                            if (e.data) {
                                var _da = e.data;
                                var d = Object.keys(_da);
                                $(d).each(function(i, e) {
                                    data += `<b>${e.toUpperCase()} : ${_da[e]}</b><br>`;
                                })
                            }
                            str += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.user}</td>
                                    <td>${e.numero_compte}</td>
                                    <td>${e.trans_id}</td>
                                    <td>${e.montant}</td>
                                    <td>${e.type}</td>
                                    <td>${e.source}</td>
                                    <td>${data}</td>
                                    <td>${e.date}</td>
                                </tr>
                                `;
                        });
                        tdata.find('tbody').html(str);
                    },
                    error: function(resp) {
                        $('[onerror]').slideDown();
                    }
                }).always(function(s) {
                    $('[loader]').fadeOut();
                })
            }
            getdata();
        })
    </script>
@endsection
