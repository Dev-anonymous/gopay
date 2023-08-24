@extends('layouts.main')
@section('title', 'Paiements')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h3 class="font-weight-bold"> PAIEMENTS REÇUS (<span nb></span>)</h3>
                        <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                            <i class="fa fa-plus-circle mr-1"></i>
                            ACCEPTER UN PAIEMENT
                        </button>
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
                                <th class="text-center">NUMERO</th>
                                <th class="text-right">DATE</th>
                            </thead>
                            <tbody>
                                {{-- foreach ($trans as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->trans_id }}</td>
                                        <td>{{ $v->montant }}</td>
                                        <td>
                                            {{ $v->tel }} <br>
                                            <small style="font-size: 12px">Reference : {{ $v->ref }}</small>
                                        </td>
                                        <td>{{ $v->date }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdladd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Accepter un paiement</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-val" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <p>
                                Saisisser le montant et le numéro du client qui veut effectuer la transation.
                            </p>
                            <hr>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required type="number" name="amount" min="1"
                                    class="form-control" />
                                <label class="form-label" for="form1Example1">Montant à payer </label>
                            </div>
                            <div class="mb-4">
                                <select class='form-control' name="devise">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required id="phone" class="form-control" />
                                <label class="form-label" for="form1Example1">Numéro</label>
                            </div>
                            <div id="rep"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" id="btnclose"
                            data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btncancel"
                            style="display: none">Annuler</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa fa-money-bill-transfer"></i>
                            Initier la transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-footer />
@endsection

@section('js-code')
    @include('files.datatable-js')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/swal/swal.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/swal/swal/swal.min.css') }}">
    <script>
        $(function() {
            $('#phone').mask('000000000');
            var fval = $('.f-val');
            var xhr = [];
            CANSHOW = true;
            var callback = function() {
                var x =
                    $.ajax({
                        url: '{{ route('marchand.api.marchand_pay_check') }}',
                        type: 'POST',
                        data: {
                            ref: REF
                        },
                        success: function(res) {
                            if (res.success) {
                                clearInterval(interv);
                                var form = fval;
                                var btn = $(':submit', form).attr('disabled', false);
                                btn.html(
                                    '<i class="fa fa-money-bill-transfer"></i> Initier la transaction'
                                );
                                btn.removeClass('btn-danger').addClass('btn-dark');
                                rep = $('#rep', form);
                                rep.html(res.message).removeClass();
                                rep.addClass('alert alert-success');
                                rep.slideDown();
                                if (CANSHOW) {
                                    CANSHOW = false;
                                    Swal.fire(
                                        'TRANSACTION EFFECTUEE !',
                                        res.message, 'success'
                                    ).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    })
                                }
                            }
                        }
                    });
                xhr.push(x);
            }
            var interv = null;
            $('#btncancel').click(function() {
                clearInterval(interv);
                $(this).hide();
                $('#btnclose').show();
                var form = fval;
                var btn = $(':submit', form).attr('disabled', false);
                btn.html(
                    '<i class="fa fa-money-bill-transfer"></i> Initier la transaction'
                );
                btn.removeClass('btn-danger').addClass('btn-dark');
                var rep = $('#rep', form);
                rep.html("Paiement annulé.").removeClass();
                rep.addClass('alert alert-warning');
                $(xhr).each(function(i, e) {
                    e.abort();
                });
            });

            fval.off('submit').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var bhtml = btn.html();
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                data += "&telephone=" + encodeURIComponent('+243' + $('#phone').val());
                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.marchand_pay_init') }}',
                    type: 'POST',
                    data: data,
                    timeout: 30000,
                    success: function(res) {
                        if (res.success == true) {
                            var l =
                                '<b class="text-danger">Transaction initialisée, demandez au client de saisir son code mobile money à son téléphone pour confirmer la transaction.</b>'
                            rep.html(l).removeClass();
                            rep.addClass('alert alert-success');
                            rep.slideDown();
                            btn.html(
                                '<i class="spinner-border spinner-border-sm"></i> En attente de validation ...'
                            );
                            btn.attr('disabled', true).removeClass('btn-dark').addClass(
                                'btn-danger');

                            clearInterval(interv);
                            REF = res.data.ref;
                            interv = setInterval(callback, 1000);
                            $('#btnclose').hide();
                            $('#btncancel').show();

                        } else {
                            var m = res.message + '<br>';
                            m += res.data?.errors_msg?.join('<br>') ?? '';
                            rep.removeClass().addClass('alert alert-danger').html(m)
                                .slideDown();
                            btn.attr('disabled', false).find('i').removeClass().addClass(
                                iclass);
                        }
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        rep.removeClass().addClass('alert alert-danger').html(mess)
                            .slideDown();
                        btn.attr('disabled', false).find('i').removeClass().addClass(iclass);
                    }

                });
            });

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
                    url: "{{ route('marchand.api.trans', ['datatable' => '']) }}&source=E-PAY",
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
                        class: 'text-center'
                    },
                    {
                        data: 'numero',
                        name: 'numero',
                        searchable: false,
                        orderable: false,
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
