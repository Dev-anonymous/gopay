@extends('layouts.main')
@section('title', 'Transfert automatique de fonds')

@section('body')
    <x-sidebar />
    <x-nav />

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold"> TRANSFERT AUTOMATIQUE DE FONDS</h6>
                        <div class="">
                            @if (getconfig('autosend') == 'yes')
                                <div class="w-100 text-center">
                                    <button class="btn btn-danger btn-sm" bdisactive>
                                        <i class="fa fa-times-circle mr-1"></i>
                                        DESACTIVER
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="font-weight-bold text-muted"> <i class="fa fa-info-circle"></i>
                                Le transfert automatique de fonds vous permet de configurer les numéros mobile money qui
                                recevront automatiquement le montant de paiement une fois vous avez une transaction.</p>
                        </div>
                        <div class="card-body">
                            @php
                                $utel = substr((int) auth()->user()->phone, 3);
                                $autosenddata = (array) json_decode(getconfig('autosenddata'));
                            @endphp
                            @if (getconfig('autosend') != 'yes')
                                <div class="w-100 text-center">
                                    <button class="btn btn-dark btn-sm" bactive>
                                        <i class="fa fa-check-circle text-success mr-1"></i>
                                        ACTIVER
                                    </button>
                                </div>
                            @else
                                <form action="#" id="fconf">
                                    <input type="hidden" name="action" value="set">
                                    <p>Une fois un paiement reçu : </p>
                                    @foreach (paysources() as $el)
                                        @php
                                            $s = $el;
                                            $perc = "percent_{$s}[]";
                                            $phone = "phone_{$s}[]";

                                            $tab = [(object) ['source' => $el, 'phone' => [$utel], 'percent' => [100]]];
                                            if (count($autosenddata)) {
                                                $tab = [];
                                                foreach ($autosenddata as $as) {
                                                    if ($as->source == $el) {
                                                        $tab[] = $as;
                                                        break;
                                                    }
                                                }
                                            }
                                            // $tab ==> always count = 1
                                            $phones = $tab[0]->phone;
                                            $perces = $tab[0]->percent;

                                        @endphp
                                        <input type="hidden" name="source[]" value="{{ $s }}">
                                        <b class="mb-2">via {{ $el }}</b>
                                        <div class="row" block>
                                            <div class="col-md-6">
                                                @foreach ($phones as $key => $d)
                                                    @php
                                                        $rnd = rand(100, 999999);
                                                    @endphp
                                                    <div class="row mb-2 g-1 rounded-lg p-3"
                                                        style="background: rgba(0, 0, 0, .075)" new>
                                                        <div class="col-md-4">
                                                            <div class="form-outline input-group">
                                                                <input required type="number" step="0.01"
                                                                    name="{{ $perc }}"
                                                                    class="form-control form-control-sm" min="0.5"
                                                                    max="100" value="{{ $perces[$key] }}" />
                                                                <label class="form-label">Envoyer</label>
                                                                <span class="input-group-text pl-0">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="d-flex">
                                                                <div>
                                                                    <div class="form-outline input-group ">
                                                                        <span class="input-group-text">+243</span>
                                                                        <input required class="form-control phone"
                                                                            name="{{ $phone }}"
                                                                            value="{{ (int) $d }}" />
                                                                        <label class="form-label">au
                                                                            numéro</label>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button bremove class="btn btn-link btn-sm text-danger"
                                                                        type="button">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div insert></div>
                                                <div class="row">
                                                    <div class="col-12 text-right">
                                                        <button badd class="btn btn-link btn-sm text-dark"
                                                            perc='{{ $perc }}' phone='{{ $phone }}'
                                                            type="button">
                                                            <i class="fa fa-plus-circle"></i> Ajouter
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row" block>
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <div id="rep"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark btn-sm mt-3" type="submit">
                                        <i class="fa fa-save mr-1"></i>
                                        VALIDER
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />


@endsection

@section('js-code')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(function() {
            $('.phone').mask('000000000');

            $('[bactive]').off('click').click(function() {
                event.preventDefault();
                var btn = $(this);
                var i = btn.find('i');
                var cl = i.attr('class');
                i.removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('config.store') }}',
                    type: 'POST',
                    data: {
                        'name': 'autosend',
                        'value': 'yes',
                    },
                    timeout: 20000,
                    success: function(res) {
                        location.reload();
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        alert(mess);
                    }
                });
            });

            $('[bdisactive]').off('click').click(function() {
                event.preventDefault();
                var btn = $(this);
                var i = btn.find('i');
                var cl = i.attr('class');
                i.removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('config.store') }}',
                    type: 'POST',
                    data: {
                        'name': 'autosend',
                        'value': 'no',
                    },
                    timeout: 20000,
                    success: function(res) {
                        location.reload();
                    },
                    error: function(resp) {
                        var mess = resp.responseJSON?.message ??
                            "Une erreur s'est produite, merci de réessayer";
                        alert(mess);
                    }
                });
            });

            var tmpl = `
                <div class="row mb-2 g-1 rounded-lg p-3" style="background: rgba(0, 0, 0, .075)" new>
                    <div class="col-md-4">
                        <div class="form-outline input-group">
                            <input required type="number" step="0.01" name="PERCDATA"
                                class="form-control form-control-sm" min="0.5" max="100" />
                            <label class="form-label">Envoyer</label>
                            <span class="input-group-text pl-0">%</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex">
                            <div>
                                <div class="form-outline input-group ">
                                    <span class="input-group-text">+243</span>
                                    <input required class="form-control phone" name="PHONEDATA" value="{{ $utel }}" />
                                    <label class="form-label">au numéro</label>
                                </div>
                            </div>
                            <div class="text-right">
                                <button bremove class="btn btn-link btn-sm text-danger" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            function onremove() {
                $('[bremove]').off('click').click(function() {
                    $(this).closest('[new]').remove();
                });
            }
            onremove();

            $('[badd]').click(function() {
                var btn = $(this);
                var perc = btn.attr('perc');
                var phone = btn.attr('phone');

                var t = tmpl.split('PERCDATA').join(perc);
                t = t.split('PHONEDATA').join(phone);

                var div = btn.closest('[block]').find('[insert]');
                div.append(t);
                document.querySelectorAll('.form-outline').forEach((formOutline) => {
                    new mdb.Input(formOutline).init();
                });
                $('.phone').off('mask').mask('000000000');
                onremove();
            });

            $('#fconf').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();

                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('config.store') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass('alert alert-success')
                                .slideDown();
                            setTimeout(() => {
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
                        rep.removeClass().addClass('alert alert-danger').html(mess).slideDown();
                    }

                }).always(function(s) {
                    btn.attr('disabled', false).find('i').removeClass().addClass(iclass);
                });
            });


        })
    </script>
@endsection
