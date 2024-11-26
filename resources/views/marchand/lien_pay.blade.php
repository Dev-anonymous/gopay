@extends('layouts.main')
@section('title', 'Accepter le paiement')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold"> LIENS DE PAIEMENT (<span nb></span>)</h6>
                        <div class="">
                            <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                                <i class="fa fa-globe mr-1"></i>
                                NOUVEAU LIEN
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
                            <thead class="table-dark text-nowrap">
                                <th></th>
                                <th>NOM</th>
                                <th>MONTANT</th>
                                <th class="text-center">MONTANT FIXE</th>
                                <th class="text-center">DEVISE FIXE</th>
                                <th class="text-center">LIEN DE PAIEMENT</th>
                                <th class="text-right">DATE</th>
                                <th></th>
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
                    <h5 class="modal-title text-dark font-weight-bold">Nouveau lien de paiement</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form id="f-add" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline">
                                <input id="form1Example1" required name="name" maxlength="100" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom du lien</label>
                            </div>
                            <label for="" class="mb-4">Ex : DON, PAIEMENT</label>
                            <div class="form-outline mb-3 input-group flex-nowrap">
                                <input required type="number" step="0.01" name="amount"
                                    class="form-control form-control-sm" />
                                <label class="form-label" for="form1Example1">Montant de paiement</label>
                                <span class="input-group-text" id="addon-wrapping">
                                    <select class='form-control form-control-sm' name="devise">
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </span>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="montant_fixe" />
                                    <label class="form-check-label" for="montant_fixe">
                                        Autoriser le payeur à payer un montant autre que celui renseigné
                                    </label>
                                </div>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <div class="form-check">
                                    <input disabled class="form-check-input" type="checkbox" name="devise_fixe"
                                        id="dfixe" />
                                    <label class="form-check-label" for="dfixe">
                                        Autoriser le payeur à payer dans n'importe quelle devise
                                    </label>
                                </div>
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
    <script src="{{ asset('js/swal/swal.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/swal/swal/swal.min.css') }}">
    <script>
        $(function() {
            function init() {
                $('[deletelink]').off('click').click(function() {
                    event.preventDefault();
                    var el = $(this);
                    var id = el.attr('id');
                    el.closest('td').find('button').html('<i class="spinner-border spinner-border-sm"></i>')
                        .attr('disabled', true);
                    $.ajax({
                        url: '{{ route('marchand.api.pay_link', '') }}/' + id,
                        type: 'delete',
                        timeout: 20000,
                        success: function(res) {
                            datatableOb.ajax.reload(null, false);
                            Swal.fire(
                                'Le lien de paiement a été supprimé !',
                                '',
                                'info'
                            )
                        },
                        error: function(resp) {
                            var mess = resp.responseJSON?.message ??
                                "Une erreur s'est produite,veuillez recharger cette page";
                            Swal.fire(
                                'Oops',
                                mess, 'error'
                            );
                        }

                    });
                });
                $('.btn-copy').off('click').click(function() {
                    event.preventDefault();
                    var id = this.value;
                    var btn = $(this);
                    btn.attr('disabled', true);
                    btn.find('i').removeClass().addClass('fa fa-check-circle text-success');
                    btn.find('span').html(' lien copié');
                    setTimeout(() => {
                        btn.find('i').removeClass().addClass('fa fa-copy');
                        btn.find('span').html('');
                        btn.attr('disabled', false);
                    }, 3000);

                    var copyText = document.getElementById("lien-" + id);
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(copyText.value);
                })
            }

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                var mf = !$('#montant_fixe')[0].checked;
                data += "&montant_fixe=" + (mf ? '1' : '0');

                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.pay_link') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass('alert alert-success')
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
                        rep.removeClass().addClass('alert alert-danger').html(mess).slideDown();
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
                    url: "{{ route('marchand.api.pay_link', ['datatable' => '']) }}",
                    beforeSend: function() {
                        $('[tdata]').closest('div').LoadingOverlay("show", {
                            maxSize: 50
                        });
                    },
                    complete: function() {
                        init();
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
                        data: 'montant',
                        name: 'montant',
                        class: 'text-nowrap text-center'
                    },
                    {
                        data: 'montant_fixe',
                        name: 'montant_fixe',
                        class: 'text-center'
                    },
                    {
                        data: 'devise_fixe',
                        name: 'devise_fixe',
                        class: 'text-center'
                    },
                    {
                        data: 'lien',
                        name: 'lien',
                        searchable: false,
                        orderable: false,
                        class: 'text-nowrap'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        class: 'text-right'
                    }, {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    },
                ]
            })).on('xhr.dt',
                function(e, settings, data, xhr) {
                    $('span[nb]').html(data.recordsTotal);
                });
        })
    </script>
@endsection
