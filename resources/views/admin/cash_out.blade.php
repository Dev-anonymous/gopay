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
                        <h3 class="font-weight-bold" title></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-error />
                    <div class="table-responsive">
                        <table tdata class="table table-hover font-weight-bold table-striped">
                            <thead class="table-dark">
                                <th>
                                    <div loader class="spinner-border spinner-border-sm"></div>
                                </th>
                                <th>TRANS. ID</th>
                                <th>MARCHAND</th>
                                <th class="text-nowrap">NUMERO COMPTE</th>
                                <th>SOLDE</th>
                                <th class="text-center">ENVOI AU</th>
                                <th class="text-center">DATE D'ENVOI</th>
                                <th class="text-center">MONTANT</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-nowrap">NOTE VALIDATION</th>
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

    <div class="d-none" template>
        <div class="modal fade" id="mdlinfo-DATA_ID" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold">Validation de la transaction</h5>
                        <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                    </div>
                    <form class="f-val" action="#">
                        <div class="modal-body">
                            <div class="bg-white rounded shadow-lg p-5">
                                <h5 class="font-weight-bold">Montant : DATA_MONTANT</h5>
                                <h5 class="font-weight-bold">Au : DATA_NUMERO</h5>
                                <hr>
                                <div class="mb-4">
                                    <input type="hidden" value="DATA_ID" name="id">
                                    <label for="st">Status</label>
                                    <select id="st" class='form-control' name="status">
                                        <option>TRAITÉE</option>
                                        <option>REJETÉE</option>
                                    </select>
                                </div>
                                <div class="form-outline mb-4">
                                    <textarea name='note_validation' class="form-control" id="textAreaExample" rows="4"></textarea>
                                    <label class="form-label" for="textAreaExample">Note de validation</label>
                                </div>
                                <div id="rep-DATA_ID"></div>
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
    </div>
    <div id="mdlzone"></div>
@endsection

@section('js-code')
    <script>
        $(function() {
            var tdata = $('[tdata]');

            function getdata() {
                $('[loader]').fadeIn();
                $.ajax({
                    url: '{{ route('admin.api.cashout') }}',
                    timeout: 20000,
                    success: function(res) {
                        $('[title]').html(res.message);
                        var str = '';
                        var template = $('[template]').html();
                        var mdls = '';
                        $(res.data).each(function(i, e) {
                            var dt = '';
                            var btn = '';
                            if (e.status == 'EN ATTENTE') {
                                var status =
                                    `<span class='badge w-100 bg-warning p-2'>${e.status}</span>`;
                            } else if (e.status == 'TRAITÉE') {
                                var status =
                                    `<span class='badge w-100 bg-success p-2'>${e.status}</span>`;
                                dt = "Le " + e.date_validation;
                            } else {
                                var status =
                                    `<span class='badge w-100 bg-danger p-2'>${e.status}</span>`;
                                dt = "Le " + e.date_validation;
                            }

                            if (!e.date_validation) {
                                btn = `<button data-toggle="modal" data-target="#mdlinfo-${e.id}" class='btn btn-outiline-dark'">
                                            <i class='fa fa-info-circle'></i>
                                        </button>`;
                            }

                            var modal = template.split('DATA_ID').join(e.id);
                            modal = modal.split('DATA_MONTANT').join(e.montant);
                            modal = modal.split('DATA_NUMERO').join(e.au_numero);

                            str += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.trans_id}</td>
                                    <td>${e.business_name}<br>${e.marchand}</td>
                                    <td>${e.numero_compte}</td>
                                    <td class="text-nowrap">${e.solde.join('<br>')}</td>
                                    <td class="text-nowrap text-center">${e.au_numero}</td>
                                    <td class="text-nowrap text-center">${e.date_denvoi}</td>
                                    <td class="text-nowrap text-center">${e.montant}</td>
                                    <td class="text-center text-nowrap">
                                        ${status} <br>
                                        <small class='text-muted mt-1' style='font-size:10px'>${dt}</small>
                                    </td>
                                    <td>${e.note_validation??'-'}</td>
                                    <td class="text-right">${e.date}</td>
                                    <td>
                                        ${btn}
                                    </td>
                                </tr>
                                `;
                            mdls += modal;
                        });
                        tdata.find('tbody').html(str);
                        $('#mdlzone').html(mdls);
                        init();

                    },
                    error: function(resp) {
                        $('[onerror]').slideDown();
                    }
                }).always(function(s) {
                    $('[loader]').fadeOut();
                });
            }
            getdata();

            function init() {
                $('.f-val').off('submit').submit(function() {
                    event.preventDefault();
                    var form = $(this);
                    var btn = $(':submit', form).attr('disabled', true);
                    var iclass = btn.find('i').attr('class');
                    btn.find('i').removeClass()
                        .addClass('spinner-border spinner-border-sm');
                    var data = form.serialize();
                    data += "&phone=" + encodeURIComponent('+243' + $('#phone').val());
                    var id = $('[name=id]', form).val();
                    rep = $('#rep-' + id, form);
                    rep.slideUp();
                    $.ajax({
                        url: '{{ route('admin.api.cashout') }}',
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
                                    getdata();
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
            }
        })
    </script>
@endsection
