@extends('layouts.main')
@section('title', 'Marchands')

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
                            AJOUTER
                        </button>
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
                                <th>BUSINESS</th>
                                <th>COMMISSION</th>
                                <th>MARCHAND</th>
                                <th>CONTACT</th>
                                <th>NUMERO COMPTE</th>
                                <th>SOLDE</th>
                                <th>DATE CREATION</th>
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
    <div class="modal fade" id="mdladd" tabindex="-1" aria-labelledby="mdladdLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="exampleModalLabel">Nouveau compte marchand</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="was-validated" id="f-add" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="name" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom du marchand</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="email" type="email" class="form-control" />
                                <label class="form-label" for="form1Example1">Email du marchand</label>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required id="phone" class="form-control" />
                                <label class="form-label" for="form1Example1">Telephone</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="business_name" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom du business</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" type="number" value="3.5" step="0.1" min="1" required name="commission" class="form-control" />
                                <label class="form-label" for="form1Example1">Commission en %</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input name="password" id="form1Example2" class="form-control" required />
                                <label class="form-label" for="form1Example2">Mot de passe</label>
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
    <div class="d-none" template>
        <div class="modal fade" id="mdlinfo-DATA_ID" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark font-weight-bold">Info du compte marchand</h5>
                        <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                    </div>
                    <div class="modal-body font-weight-bold">
                        <h5>Clés API DATA_BUSINESS</h5>
                        <div class="">
                            <table class="table table-striped table-hover text-nowrap text-justify">
                                <thead class="table-dark">
                                    <tr>
                                        <th>TYPE</th>
                                        <th>CLEF</th>
                                        <th>STATUS</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="rep-DATA_2ID"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mdlzone"></div>
@endsection

@section('js-code')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(function() {
            var tdata = $('[tdata]');

            function getdata() {
                $('[loader]').fadeIn();
                $.ajax({
                    url: '{{ route('admin.api.marchand') }}',
                    timeout: 20000,
                    success: function(res) {
                        $('[title]').html(res.message);
                        var str = '';
                        var template = $('[template]').html();

                        var mdls = '';
                        $(res.data).each(function(i, e) {
                            var modal = template.split('DATA_ID').join(e.id);
                            modal = modal.split('DATA_BUSINESS').join(e.business_name);
                            var apikeys = '';
                            e.apikey.map(function(el) {
                                if (el.active == 1) {
                                    var txt =
                                        '<i class="fa fa-times-circle"></i> Désactiver';
                                    var v = 0;
                                    var st =
                                        '<span class="badge bg-success p-2"><i class="fa fa-check-circle"></i> ACTIVEE</span>';
                                } else {
                                    var txt =
                                        '<i class="fa fa-check-circle"></i> Activer';
                                    var v = 1;
                                    var st =
                                        '<span class="badge bg-danger p-2"><i class="fa fa-check-circle"></i> DESACTIVEE</span>';
                                }
                                apikeys += `
                                    <tr>
                                        <td>${el.type}</td>
                                        <td>${el.key}</td>
                                        <td>${st}</td>
                                        <td>
                                            <button class="btn btn-dark btn-toggle" value='${el.id}.${v}.${e.id}'>
                                                ${txt}
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                modal = modal.split('DATA_2ID').join(e.id);
                            });
                            modal = modal.split('<tbody></tbody>').join(
                                `<tbody>${apikeys}</tbody>`);
                            mdls += modal;
                            str += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.business_name}</td>
                                    <td>${e.commission}</td>
                                    <td>${e.name}</td>
                                    <td>${e.phone}<br>${e.email}</td>
                                    <td>${e.numero_compte}</td>
                                    <td>${e.solde.join('<br>')}</td>
                                    <td>${e.date_creation}</td>
                                    <td>
                                        <button data-toggle="modal" data-target="#mdlinfo-${e.id}" class='btn btn-outiline-dark'">
                                            <i class='fa fa-info-circle'></i>
                                        </button>
                                    </td>
                                </tr>
                                `;
                        });
                        tdata.find('tbody').html(str);
                        $('#mdlzone').html(mdls);
                        initinfo();
                    },
                    error: function(resp) {
                        $('[onerror]').slideDown();
                    }
                }).always(function(s) {
                    $('[loader]').fadeOut();
                })
            }

            function initinfo() {
                $('.btn-toggle').off('click').click(function() {
                    event.preventDefault();
                    var btn = $(this).attr('disabled', true);
                    var t = this.value.toString().split('.');
                    var idkey = t[0];
                    var status = t[1];
                    var idm = t[2];
                    var iclass = btn.find('i').attr('class');
                    btn.find('i').removeClass()
                        .addClass('spinner-border spinner-border-sm');
                    rep = $('#rep-' + idm);
                    rep.slideUp();
                    var data = {
                        id: idkey,
                        active: status
                    };
                    $.ajax({
                        url: '{{ route('admin.api.apikeys') }}',
                        type: 'POST',
                        data: data,
                        timeout: 20000,
                        success: function(res) {
                            if (res.success == true) {
                                rep.html(res.message).removeClass().addClass(
                                        'alert alert-success')
                                    .slideDown();
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
                });
            }
            getdata();

            $('#phone').mask('000000000');

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
                    url: '{{ route('admin.api.marchand') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            form[0].reset();
                            getdata();
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
