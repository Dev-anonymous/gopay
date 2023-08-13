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
                        <h3 class="font-weight-bold" title></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-sm table-condensed table-hover table-striped">
                            <thead class="table-dark">
                                <th>
                                    <div loader class="spinner-border spinner-border-sm"></div>
                                </th>
                                <th>UTILISATEUR</th>
                                <th>CONTACT</th>
                                <th>SUJET</th>
                                <th>MESSAGE</th>
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
                    url: '{{ route('admin.api.feedback') }}',
                    timeout: 20000,
                    success: function(res) {
                        $('[title]').html(res.message);
                        var str = '';
                        $(res.data).each(function(i, e) {
                            str += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.nom}</td>
                                    <td>${e.telephone} <br> ${e.email}</td>
                                    <td>${e.sujet}</td>
                                    <td>${e.message}</td>
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
