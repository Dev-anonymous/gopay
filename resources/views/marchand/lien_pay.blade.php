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
                        <h3 class="font-weight-bold">LIENS DE PAIEMENT ({{ count($data) }})</h3>
                        <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                            <i class="fa fa-plus-circle mr-1"></i>
                            NOUVEAU LIEN
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-hover font-weight-bold table-striped text-nowrap">
                            <thead class="table-dark">
                                <th></th>
                                <th>CODE</th>
                                <th>MONTANT</th>
                                <th>ENVOI AU</th>
                                <th class="text-center">STATUS</th>
                                <th>NOTE VALIDATION</th>
                                <th>DATE</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $k => $v)
                                    @php
                                        $dt = '';
                                        if ($v->status == 'EN ATTENTE') {
                                            $status = "<span class='badge bg-warning p-2'>$v->status</span>";
                                        } elseif ($v->status == 'TRAITÉE') {
                                            $status = "<span class='badge bg-success p-2'>$v->status</span>";
                                            $dt = "Le $v->date_validation";
                                        } else {
                                            $status = "<span class='badge bg-danger p-2'>$v->status</span>";
                                            $dt = "Le $v->date_validation";
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->code }}</td>
                                        <td>{{ $v->montant }}</td>
                                        <td>{{ $v->au_numero }}</td>
                                        <td class="text-center">
                                            {!! $status !!}
                                            <br>
                                            <i class='text-muted small mt-1'>{{ $dt }}</i>
                                        </td>
                                        <td>{{ $v->note_validation }}</td>
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

    <div class="modal fade" id="mdladd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Nouveau lien de paiement</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-val" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="montant" class="form-control" />
                                <label class="form-label" for="form1Example1">Transferer un montant de </label>
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
                                <label class="form-label" for="form1Example1">Au numero</label>
                            </div>
                            <div id="rep"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-dark btn-sm" disabled>
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
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
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
                            setTimeout(() => {
                                $('.modal-backdrop.fade.show,.modal.fade.show')
                                    .remove();
                                location.reload();
                            }, 3000);
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

            })

        })
    </script>
@endsection
