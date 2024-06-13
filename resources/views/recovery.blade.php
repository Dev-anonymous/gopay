@extends('layouts.main')
@section('title', 'Mot de passe oublié')

@section('body')
    <x-nav-app />

    <div id="intro" class="bg-image shadow-2-strong">
        <div class="mask" style="background-color: rgba(0, 0, 0, 0.8);">
            <div class="container d-flex align-items-center h-100">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-5 col-md-8">
                        @if (!$show)
                            <form action="#" class="rounded shadow-5-strong p-5"
                                style="background-color: rgba(0, 0, 0, 0.76);" id="f-rec">
                                <div class="text-center">
                                    <h5 class="mb-3 font-weight-bold text-white">Mot de passe oublié</h5>
                                </div>
                                <div class="text-danger mb-3">
                                    Pour récuperer votre compte, veuillez entrer votre numéro de téléphone ( avec indicatif,
                                    ex
                                    : 24399...) ou votre email (jarvis@email.com).
                                </div>
                                <div class="form-outline mb-4">
                                    <input id="form1Examplezzz" name="login" class="form-control" />
                                    <label class="form-label text-white" for="form1Examplezzz">Email ou Téléphone</label>
                                </div>
                                <div id="rep">
                                </div>
                                <button type="submit" class="btn btn-dark btn-block">
                                    <i class="fa fa-check-circle"></i>
                                    Valider
                                </button>
                                <div class="row mb-4 mt-4">
                                    <a href="{{ route('app.login') }}" class="text-white d-flex justify-content-end"><i>Se
                                            connecter</i></a>
                                </div>
                            </form>
                        @else
                            <div class="ps-form__content pb-5">
                                @if ($error)
                                    <div class="text-center">
                                        <p class="font-weight-bold text-danger">
                                            {{ $msg }}
                                        </p>
                                        <p>
                                            <a href="{{ route('recoveryview') }}">Réinitialiser mon mot de passe</a>
                                        </p>
                                    </div>
                                @else
                                    <form action="#" class="rounded shadow-5-strong p-5"
                                        style="background-color: rgba(0, 0, 0, 0.76);" id="f-pass">
                                        <div class="text-danger mb-3">
                                            Saisissez votre nouveau mot de passe et confirmer.
                                        </div>
                                        <div class="form-outline mb-4">
                                            <input type="password" name="pass" required id="form1Example2"
                                                class="form-control" />
                                            <label class="form-label text-white" for="form1Example2">Mot de passe</label>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <input type="password" name="cpass" required id="form1Example2yy"
                                                class="form-control" />
                                            <label class="form-label text-white" for="form1Example2yy">Confirmez le Mot de
                                                passe</label>
                                        </div>
                                        <div id="rep">
                                        </div>
                                        <button type="submit" class="btn btn-dark btn-block">
                                            <i class="fa fa-check-circle"></i>
                                            Valider
                                        </button>
                                        <div class="row mb-4 mt-4">
                                            <a href="{{ route('app.login') }}"
                                                class="text-white d-flex justify-content-end"><i>Se
                                                    connecter</i></a>
                                        </div>
                                    </form>
                                @endif


                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
@endsection

@section('js-code')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    // 'Authorization': 'Bearer ' + localStorage.getItem('_token'),
                    'Accept': 'application/json'
                }
            });


            @if (!$show)
                $('#f-rec').submit(function() {
                    event.preventDefault();
                    var form = $(this);
                    var btn = $(':submit', form).attr('disabled', true)
                    btn.find('i').removeClass()
                        .addClass('spinner-border spinner-border-sm');
                    var data = form.serialize();
                    var r = new URL(location.href).searchParams.get('r');
                    if (r) {
                        data = data + '&r=' + encodeURIComponent(r)
                    }
                    rep = $('#rep');
                    rep.slideUp();
                    $.ajax({
                        url: '{{ route('api.recovery') }}',
                        type: 'post',
                        data: data,
                        timeout: 20000,
                        success: function(res) {
                            if (res.success == true) {
                                rep.html(res.message).removeClass().addClass(
                                        'alert alert-success')
                                    .slideDown();
                                setTimeout(() => {
                                    window.location.assign('{{ route('app.login') }}');
                                }, 10000);
                            } else {
                                btn.attr('disabled', false).find('i').removeClass(
                                        'spinner-border spinner-border-sm')
                                    .addClass('fa fa-check-circle');
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
                            btn.attr('disabled', false).find('i').removeClass().addClass(
                                'fa fa-check-circle');
                        }
                    });
                });
            @endif

            @if (!$error)
                $('#f-pass').submit(function() {
                    event.preventDefault()
                    var form = $(this)
                    var btn = $(':submit', form).attr('disabled', true)
                    var txt = btn.html();
                    btn.html(`<i class='spinner-border'></i>`);
                    var data = form.serialize();
                    data += "&token={{ request()->token }}"

                    rep = $('#rep');
                    rep.slideUp()

                    $.ajax({
                        url: '{{ route('api.recovery-complete') }}',
                        type: 'post',
                        data: data,
                        timeout: 20000,
                    }).done(function(res) {
                        btn.attr('disabled', false).find('span').removeClass();
                        btn.html(txt);
                        rep.removeClass();
                        if (res.success) {
                            $(':input', form).attr('disabled', true);
                            btn.slideUp();
                            localStorage.setItem('user-token', res.data.token);
                            rep.addClass('alert alert-success w-100').html(res.message)
                                .slideDown();
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            rep.addClass('alert alert-danger w-100').html(res.message)
                                .slideDown();
                        }


                    }).fail(function(res) {
                        btn.attr('disabled', false).find('span').removeClass();
                        btn.html(txt);
                        res = res.responseJSON;
                        if (res) {
                            m = res.message + '<br>';
                            m += res.data.errors_msg.join('<br>')
                            rep.removeClass().addClass('alert alert-danger w-100').html(m)
                                .slideDown()
                        };
                    })
                })
            @endif



        })
    </script>
@endsection
