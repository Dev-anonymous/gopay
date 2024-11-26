@extends('layouts.main')
@section('title', 'Envoi fonds')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold">TRANSFERT DE FONDS (<span nb></span>)</h6>
                        <div class="">
                            <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                                <i class="fa fa-plus-circle mr-1"></i>
                                NOUVEAU TRANSFERT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-hover font-weight-bold table-striped" style="width: 100%">
                            <thead class="table-dark">
                                <th></th>
                                <th>TRANS. ID</th>
                                <th class="text-center">MONTANT</th>
                                <th class="text-center">ENVOI AU</th>
                                <th class="text-center">DATE D'ENVOI</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">NOTE VALIDATION</th>
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

    <div class="modal fade" id="mdladd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Nouveau transfert</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-val" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <h6 class="font-weight-bold small text-danger">

                            </h6>
                            <div class="alert alert-warning">
                                <b>
                                    <i class="fa fa-info-circle"></i> Les frais de transaction sont de {{ $frais }}%
                                    sur le montant
                                </b>
                            </div>
                            <div class="alert alert-danger">
                                <b>
                                    <i class="fa fa-exclamation-triangle"></i> Il est fortement récommandé d'utiliser un
                                    numéro Orange pour le transfert de fonds.
                                </b>
                            </div>
                            <hr>
                            <div class="form-outline mb-3 input-group flex-nowrap">
                                <input required type="number" step="0.01" name="montant"
                                    class="form-control form-control-sm" />
                                <label class="form-label" for="form1Example1">Transférer un montant de</label>
                                <span class="input-group-text" id="addon-wrapping">
                                    <select class='form-control form-control-sm' name="devise">
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </span>
                            </div>
                            @php
                                $tel = substr((int) auth()->user()->phone, 3);
                            @endphp
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required id="phone" class="form-control" value="{{ $tel }}" />
                                <label class="form-label" for="form1Example1">Au numero</label>
                            </div>
                            <div id="rep"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa fa-save"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    @include('files.datatable-js')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/swal/swal.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/swal/swal/swal.min.css') }}">
    <script>
        $(function() {
            $('#phone').mask('000000000');
            $('.f-val').off('submit').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                data += "&telephone=" + encodeURIComponent('+243' + $('#phone').val());
                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.demande_trans') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass(
                                    'alert alert-success')
                                .slideDown();
                            form[0].reset();
                            datatableOb.ajax.reload(null, false);
                        } else {
                            var m = res.message + '<br>';
                            m += res.data?.errors_msg?.join('<br>') ?? '';
                            rep.removeClass().addClass('alert alert-danger').html(m)
                                .slideDown();
                        }
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        rep.removeClass().addClass('alert alert-danger').html(mess)
                            .slideDown();
                    }

                }).always(function(s) {
                    btn.attr('disabled', false).find('i').removeClass().addClass(iclass);
                });
            });

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
                    url: "{{ route('marchand.api.demande_trans', ['datatable' => '']) }}",
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
                        data: 'au_numero',
                        name: 'au_numero',
                        class: 'text-center'
                    },
                    {
                        data: 'date_denvoi',
                        name: 'date_denvoi',
                        class: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        class: 'text-center'
                    },
                    {
                        data: 'note_validation',
                        name: 'note_validation',
                        searchable: false,
                        orderable: false,
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
