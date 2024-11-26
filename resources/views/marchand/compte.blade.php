@extends('layouts.main')
@section('title', 'Compte')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold">MON COMPTE</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body font-weight-bold">
                            <table class="table table-hover font-weight-bold table-striped table-light">
                                <thead>
                                    <tr>
                                        <th colspan="2">MON PROFIL</th>
                                    </tr>
                                </thead>
                                @php
                                    $user = auth()->user();
                                    $tel = (int) $user->phone;
                                    $tel = substr($tel, 3);
                                @endphp
                                <tbody>
                                    <tr>
                                        <td>NOM</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>TELEPHONE</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>EMAIL</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>NOM DU BUSINESS</td>
                                        <td>{{ $user->business_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>TYPE DE COMPTE</td>
                                        <td>MARCHAND</td>
                                    </tr>
                                    <tr>
                                        <td>DATE CREATION</td>
                                        <td>{{ $user->created_at?->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#mdledit">
                                    <i class="fa fa-edit"></i>
                                    Modifier mes infos
                                </button>
                                <button class="btn btn-dark btn-sm mr-3" data-toggle="modal" data-target="#mdlpass">
                                    <i class="fa fa-key"></i>
                                    Modifier mon mot de passe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    <div class="modal fade" id="mdledit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Modification</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-val" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="name" value="{{ $user->name }}"
                                    class="form-control" />
                                <label class="form-label" for="form1Example1">Nom</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" required name="email" type="email" value="{{ $user->email }}"
                                    class="form-control" />
                                <label class="form-label" for="form1Example1">Email</label>
                            </div>
                            <div class="form-outline mb-4 input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">+243</span>
                                <input required id="phone" class="form-control" value="{{ $tel }}" />
                                <label class="form-label" for="form1Example1">Telephone</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input id="form1Example1" disabled value="{{ $user->business_name }}"
                                    class="form-control" />
                                <label class="form-label" for="form1Example1">Nom du business</label>
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
    <div class="modal fade" id="mdlpass" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Modification du mot de passe</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-pass" action="#">
                    <div class="modal-body">
                        <div class="bg-white rounded shadow-lg p-5">
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="form1Example2" class="form-control" required />
                                <label class="form-label" for="form1Example2">Mot de passe actuel</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="newpassword" id="form1Example2" class="form-control"
                                    required />
                                <label class="form-label" for="form1Example2">Nouveau Mot de passe</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="cnewpassword" id="form1Example2" class="form-control"
                                    required />
                                <label class="form-label" for="form1Example2">Confirmer nouveau mot de passe</label>
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
                data += "&phone=" + encodeURIComponent('+243' + $('#phone').val());
                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.update_compte') }}',
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
            $('.f-pass').off('submit').submit(function() {
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
                    url: '{{ route('marchand.api.update_passe') }}',
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
