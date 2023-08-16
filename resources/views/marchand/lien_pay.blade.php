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
                        <h3 class="font-weight-bold" title></h3>
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
                                <th>
                                    <div loader class="spinner-border spinner-border-sm"></div>
                                </th>
                                <th>NOM</th>
                                <th>MONTANT</th>
                                <th class="text-center">MONTANT FIXE</th>
                                <th class="text-center">DEVISE FIXE</th>
                                <th class="text-center">LIEN DE PAIEMENT</th>
                                <th>DATE</th>
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
                                <input id="form1Example1" required name="nom" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom du lien</label>
                            </div>
                            <label for="" class="mb-4">Ex : DON, PAIEMENT</label>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="amount" type="number" min="1"
                                    class="form-control" />
                                <label class="form-label" for="form1Example1">Montant de paiement</label>
                            </div>
                            <div class="mb-4">
                                <select class='form-control' name="devise">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="montant_fixe" id="eer" />
                                    <label class="form-check-label" for="eer">
                                        Autoriser le payeur à payer un montant autre que celui renseigné
                                    </label>
                                </div>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="devise_fixe" id="dfixe" />
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
    {{-- <script src="{{ asset('js/jquery.mask.min.js') }}"></script> --}}
    <script>
        $(function() {
            // $('#phone').mask('000000000');
            var tdata = $('[tdata]');

            function getdata() {
                $('[loader]').fadeIn();
                $.ajax({
                    url: '{{ route('marchand.api.pay_link') }}',
                    timeout: 20000,
                    success: function(res) {
                        $('[title]').html(res.message);
                        var str = '';

                        $(res.data).each(function(i, e) {
                            if (e.devise_fixe) {
                                var dfixe = '<span class="badge p-2 bg-success" >OUI</span>';
                            } else {
                                var dfixe = '<span class="badge p-2 bg-warning" >OUI</span>';
                            }
                            if (e.montant_fixe) {
                                var mfixe = '<span class="badge p-2 bg-success" >OUI</span>';
                            } else {
                                var mfixe = '<span class="badge p-2 bg-warning" >OUI</span>';
                            }

                            var lien =
                                `<a href="${e.lien}" target='_blank' class='btn btn-link'><i class='fa fa-globe-africa'></i> Lien</a>`
                            lien +=
                                `<button class='btn btn-sm btn-copy' value='${e.id}'><i class="fa fa-copy"></i><span></span></button>`

                            str += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.nom}</td>
                                    <td>${e.montant}</td>
                                    <td class="text-center">${mfixe}</td>
                                    <td class="text-center">${dfixe}</td>
                                    <td class="text-center">${lien}</td>
                                    <td>${e.date}</td>
                                    <td>
                                        <input value='${e.lien}' id='lien-${e.id}' class='d-none'>
                                        <button class="btn btn-link dropdown-toggle mr-4 text-dark" type="button" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                           <i class='fa fa-trash'></i> Supprimer
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" id="${e.id}" deletelink href="#">Confirmer</a>
                                        </div>
                                    </td>
                                </tr>
                                `;
                        });
                        tdata.find('tbody').html(str);
                        init();
                    },
                    error: function(resp) {
                        $('[onerror]').slideDown();
                    }
                }).always(function(s) {
                    $('[loader]').fadeOut();
                })
            }

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
                            alert(res.message);
                            getdata();
                        },
                        error: function(resp) {
                            var mess = resp.responseJSON?.message ??
                                "Une erreur s'est produite, merci de réessayer";
                            alert(mess);
                        }

                    });
                });
                $('.btn-copy').off('click').click(function() {
                    event.preventDefault();
                    var id = this.value;
                    var btn = $(this);
                    btn.attr('disabled', true);
                    btn.find('i').removeClass().addClass('fa fa-check-circle text-success fa-2x');
                    btn.find('span').html(' lien copié');
                    setTimeout(() => {
                        btn.find('i').removeClass().addClass('fa fa-copy');
                        btn.find('span').html('');
                        btn.attr('disabled', false);
                    }, 3000);

                    var copyText = document.getElementById("lien-"+id);
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(copyText.value);
                })
            }
            getdata();

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                data += "&phone=" + encodeURIComponent('+243' + $('#phone').val());
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
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
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
            })
        })
    </script>
@endsection
