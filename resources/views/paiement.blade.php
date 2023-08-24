@extends('layouts.main')
@section('title', 'Paiement')

@section('body')
    <x-nav-app />

    <div id="intro" class="bg-image shadow-2-strong">
        <div class="mask" rrstyle="background-color: rgba(0, 0, 0, 0.8);">
            <div class="container d-flex align-items-center h-100">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-6 col-md-8 col-sm-12">
                        <form class="bg-white text-dark rounded shadow-5-strong p-5 mt-2" id="f-log" accept="#"
                            style="ebackground-color: rgba(0, 0, 0, 0.76);">
                            @if ($valide)
                                <div class="">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-2 font-weight-bold">Paiement | {{ config('app.name') }}</h6>
                                        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#minfo">
                                            <i class="fa fa-info-circle"></i>
                                            Comment payer ?
                                        </a>
                                    </div>
                                    <hr class="m-0 mb-2">
                                    <div class="w-100 text-center">
                                        <i>{{ $link->nom }}</i>
                                    </div>
                                    <hr class="m-0 mb-3">

                                    <div class="mb-3">
                                        <div class="text-center">
                                            <div class="">
                                                <small class="p-0 font-weight-bold">
                                                    <i class="fa fa-lock text-success"></i>
                                                    Nous utilisons les transactions sécurisées et acceptons les paiements
                                                    par :
                                                </small>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <a class="m-1">
                                                    <img class="img-thumbnail"
                                                        src="{{ asset('img/payment-method/airtel.png') }}" width="100px"
                                                        height="50px" alt="" />
                                                </a>
                                                <a class="m-1">
                                                    <img class="img-thumbnail"
                                                        src="{{ asset('img/payment-method/vodacom.png') }}" width="100px"
                                                        height="50px" alt="" />
                                                </a>
                                                <a class="m-1">
                                                    <img class="img-thumbnail"
                                                        src="{{ asset('img/payment-method/orange.png') }}" width="100px"
                                                        height="50px" alt="" />
                                                </a>
                                                <a class="m-1">
                                                    <img class="img-thumbnail"
                                                        src="{{ asset('img/payment-method/afrimoney.png') }}" width="100px"
                                                        height="50px" alt="" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2  text-dark rounded-lg mb-3"
                                        style="background-color: rgba(0, 0, 0, 0.15);">
                                        <h6 class="font-weight-bold small">
                                            Vous etes sur la page de paiement pour le compte de
                                            <i class="text-danger"> {{ strtoupper($user->business_name) }}</i>
                                        </h6>
                                        <h6 class="font-weight-bold small">
                                            Montant initial de paiement :
                                            <i>{{ formatMontant($link->montant, $link->devise) }}</i>
                                        </h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="link" value="{{ $link->id }}">
                                    <div class="col">
                                        <div class="form-outline mb-4">
                                            <input id="form1Example1" required value="{{ $link->montant }}" type="number"
                                                name="amount" min="1" class="form-control"
                                                @if ($link->montant_fixe == 1) disabled @endif />
                                            <label class="form-label" for="form1Example1">Montant à payer </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <select class='form-control' name="devise" disabled>
                                                <option @if ($link->devise == 'CDF') selected @endif>CDF</option>
                                                <option @if ($link->devise == 'USD') selected @endif>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-outline mb-4 input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">+243</span>
                                    <input required id="phone" class="form-control" />
                                    <label class="form-label" for="form1Example1">Numéro mobile money</label>
                                </div>
                                <div id="rep"></div>
                                <div class="w-100">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fa fa-money-check-dollar"></i>
                                        Payer
                                    </button>
                                    <button type="button" class="btn btn-light mr-2" id="btncancel"
                                        style="display: none">Annuler
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-danger text-center">
                                    <b>
                                        <i class="fa fa-exclamation-triangle"></i>
                                        LIEN DE PAIEMENT INVALID
                                    </b>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
    <div class="modal fade" id="minfo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold">Comment payer !</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="f-val" action="#">
                    <div class="modal-body">
                        <p>
                            Pour effectuer un paiement :
                        </p>
                        <ul>
                            <li>Saisissez votre numéro mobile money puis valider,</li>
                            <li>Patientez quelques secondes,</li>
                            <li>une fenêtre apparaitre à votre téléphone mobile, confirmez la
                                transaction en saisissant votre pin mobile money puis valider, votre paiement sera
                                directement pris en compte dans quelques secondes.
                            </li>
                            <li>Votre paiement est effectué! et vous recevrez un message de confirmation.</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('js/swal/swal.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/swal/swal/swal.min.css') }}">
    <script>
        $('#phone').mask('000000000');

        $(function() {
            @if ($valide)
                $.ajaxSetup({
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                CANSHOW = true;
                var xhr = [];

                var callback = function() {
                    var x =
                        $.ajax({
                            url: '{{ route('web.pay.check') }}',
                            type: 'POST',
                            data: {
                                ref: REF,
                                link: '{{ $link->id }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    clearInterval(interv);
                                    var form = $('#f-log');
                                    var btn = $(':submit', form).attr('disabled', false);
                                    btn.html(
                                        '<i class="fa fa-money-check-dollar"></i> Payer'
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
                                            res.message +
                                            " \nVous pouvez effectuer un autre paiement ou fermer cette page.",
                                            'success'
                                        ).then((result) => {
                                            if (result.isConfirmed) {
                                                CANSHOW = true;
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
                    var form = $('#f-log');
                    var btn = $(':submit', form).attr('disabled', false);
                    btn.html(
                        '<i class="fa fa-money-check-dollar"></i> Payer'
                    );
                    btn.removeClass('btn-danger').addClass('btn-dark');
                    var rep = $('#rep', form);
                    rep.html("Paiement annulé.").removeClass();
                    rep.addClass('alert alert-warning');
                    $(xhr).each(function(i, e) {
                        e.abort();
                    });
                });

                $('#f-log').submit(function() {
                    event.preventDefault();
                    var form = $(this);
                    var btn = $(':submit', form).attr('disabled', true);
                    var bhtml = btn.html();
                    var iclass = btn.find('i').attr('class');
                    btn.find('i').removeClass()
                        .addClass('spinner-border spinner-border-sm');

                    var disabled = form.find(':input:disabled').removeAttr('disabled');
                    var serialized = form.serialize();
                    disabled.attr('disabled', 'disabled');
                    var data = serialized;
                    data += "&phone=" + encodeURIComponent('+243' + $('#phone').val());
                    rep = $('#rep', form);
                    rep.slideUp();
                    $.ajax({
                        url: '{{ route('web.pay.init') }}',
                        type: 'POST',
                        data: data,
                        timeout: 30000,
                        success: function(res) {
                            if (res.success == true) {
                                rep.html(res.message).removeClass();
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
                            btn.attr('disabled', false).find('i').removeClass().addClass(
                                iclass);
                        }

                    });

                })
            @else
                setTimeout(() => {
                    Swal.fire(
                        'LIEN DE PAIEMENT INVALID', "Votre lien de paiement n'est pas valide !",
                        'error'
                    )
                }, 1000);
            @endif
        })
    </script>
@endsection
