@extends('layouts.main')
@section('title', 'Comptes de paiement')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold"> COMPTES DE PAIEMENT (<span nb></span>)</h6>
                        <div class="">
                            <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdladd">
                                <i class="fa fa-user-plus mr-1"></i>
                                NOUVEAU COMPTE
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
                                {{-- <th>RÔLE</th> --}}
                                <th>TEL.</th>
                                <th>EMAIL</th>
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
                    <h5 class="modal-title text-dark font-weight-bold">Nouveau compte</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form id="f-add" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="name" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom de l'utilisateur</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="email" type="email" class="form-control" />
                                <label class="form-label" for="form1Example1">Email</label>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required class="form-control phone" />
                                <label class="form-label" for="form1Example1">Telephone</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="form1Example2" class="form-control" required />
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

    <div class="modal fade" id="mdledit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Mise à jour du compte</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form id="f-edit" action="#">
                    <input type="hidden" name="id" id="">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="name" class="form-control" />
                                <label class="form-label" for="form1Example1">Nom de l'utilisateur</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="email" type="email" class="form-control" />
                                <label class="form-label" for="form1Example1">Email</label>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required class="form-control phone" />
                                <label class="form-label" for="form1Example1">Telephone</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="form1Example2" class="form-control" />
                                <label class="form-label" for="form1Example2">Mot de passe (optionnel)</label>
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
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(function() {
            $('.phone').mask('000000000');

            function init() {
                $('[deletelink]').off('click').click(function() {
                    event.preventDefault();
                    var el = $(this);
                    var id = el.attr('id');
                    el.closest('td').find('button[delete]').html('<i class="spinner-border spinner-border-sm"></i>')
                        .attr('disabled', true);
                    $.ajax({
                        url: '{{ route('marchand.api.users', '') }}/' + id,
                        type: 'delete',
                        timeout: 20000,
                        success: function(res) {
                            datatableOb.ajax.reload(null, false);
                            Swal.fire(
                                'Le compte a été supprimé !',
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
                    }).always(function(){
                        datatableOb.ajax.reload(null, false);
                    });
                });

                $('.bedit').off('click').click(function() {
                    var btn = $(this);
                    let data = JSON.parse(btn.val());
                    var mdl = $('#mdledit');
                    var form = $('#f-edit');

                    var phone = data.phone;
                    if (phone) {
                        phone = phone.substr(phone.length - 9);
                    }
                    $('[name=id]', form).val(data.id);
                    $('[name=name]', form).val(data.name);
                    $('[name=email]', form).val(data.email);
                    $('.phone', form).val(phone);

                    mdl.addClass('fade show');
                    const modal = new mdb.Modal(mdl[0])
                    modal.show();
                });
            }

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                data += "&phone=" + encodeURIComponent('+243' + $('.phone', form).val());

                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.users') }}',
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
            $('#f-edit').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                data += "&phone=" + encodeURIComponent('+243' + $('.phone', form).val());
                let id = $('[name=id]', form).val();

                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.users') }}/' + id,
                    type: 'PUT',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass('alert alert-success')
                                .slideDown();
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
                    url: "{{ route('marchand.api.users', ['datatable' => '']) }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        class: 'text-nowrap text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        class: 'text-center'
                    },
                    {
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
