@extends('layouts.main')
@section('title', 'Payout API')

@section('body')
    <x-sidebar />
    <x-nav />
    <style>
        .blockquote {
            border-left: 2px solid rgba(0, 0, 0, 5);
            padding-left: 10px;
            background: rgba(38, 38, 38, .15);
            border-radius: 5px
        }
    </style>

    <div class="mdb-page-content page-intro bg-white">
        <div class="container py-3 ">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between mt-5 mb-3">
                        <h6 class="font-weight-bold">INTEGRATION API PAYOUT</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body font-weight-bold">
                            <div class="table-responsive">
                                <table class="table table-hover font-weight-bold table-striped">

                                    <thead class="table-dark">
                                        <tr>
                                            <th>CLEF PAYOUT</th>
                                            <th>STATUS</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $apikeys = auth()->user()->apikeys()->where('type', 'payout')->get();
                                        @endphp
                                        @foreach ($apikeys as $el)
                                            <tr>
                                                <td key>{{ $el->key }}</td>
                                                <td>
                                                    @if ($el->active)
                                                        <span class="badge bg-success p-2" style="cursor: pointer"
                                                            data-toggle="popover" data-trigger="hover"
                                                            data-content="La clef est prête à être utilisée dans vos projet en mode production.">
                                                            <i class="fa fa-check-circle mr-1"></i> ACTIVE
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger p-2" style="cursor: pointer"
                                                            data-toggle="popover" data-trigger="hover"
                                                            data-content="La clef est désactivée, elle ne peut être utilisée.">
                                                            <i class="fa fa-ban mr-1"></i> NON ACTIVE
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="d-flex justify-content-end">
                                                    <button class="btn btn-danger ml-2" data-toggle="modal"
                                                        data-target="#mdlrev">Revoquer</button>
                                                    @if ($el->active)
                                                        <button baction value="{{ $el->id }}.0"
                                                            class="btn btn-danger ml-2" data-toggle="modal"
                                                            data-target="#mdldis">Désactiver</button>
                                                    @else
                                                        <button baction value="{{ $el->id }}.1"
                                                            class="btn btn-success ml-2" data-toggle="modal"
                                                            data-target="#mdldis">Activer</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card p-3 mt-3">
                        <div class="alert alert-danger w-100 mb-0">
                            <b>
                                <i class="fa fa-info-circle"></i> La clé PayOut est une clé très sensible, gardez la bien
                                en privé et faites vos requêtes de demande PayOut uniquement depuis votre back-end.
                            </b>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="font-weight-bold">API endpoints</h6>
                        </div>
                        <div class="card-body">
                            <p>
                                Vous pouvez envoyer de l'argent de votre Wallet GoPAY depuis votre système ou plateforme en
                                planifiant l'envoi de fond.
                            </p>
                            <h6 class="text-danger font-italic font-weight-bold">Afficher votre solde GoPAY</h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <small>/GET : {{ route('payout.balanceV1') }} <br>
                                        # Afiche le solde de votre Wallet. <br>
                                        Header : x-api-key:[PAYOUT_API_KEY] <br>
                                    </small>
                                </div>
                                <small>
                                    Requ&ecirc;te PHP Curl
                                </small>

                                <pre class="text-dark" style="font-size: 12px">
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => '{{ route('payout.balanceV1') }}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'x-api-key: '. PAYOUT_API_KEY
    ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
</pre>
                                <small>Réponse :</small>
                                <div id="jsonbalance"></div>
                            </blockquote>

                            <h6 class="text-danger font-italic font-weight-bold">Récuperer la list de transferts d'argent
                            </h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <small>/GET : {{ route('payout.transfertV1') }}<br>
                                        # Affiche la liste des vos transferts d'argent <br>
                                        Header : x-api-key:[PAYOUT_API_KEY]
                                    </small>
                                </div>
                                <small>
                                    Requ&ecirc;te PHP Curl
                                </small>

                                <pre class="text-dark" style="font-size: 12px">
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => '{{ route('payout.transfertV1') }}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'x-api-key: '. PAYOUT_API_KEY
    ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
</pre>
                                <small>Réponse :</small>
                                <div id="jsongettrans"></div>
                            </blockquote>

                            <h6 class="text-danger font-italic font-weight-bold">Envoi d'argent
                            </h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <small>/POST : {{ route('payout.transfertV1') }}<br>
                                        # Permet d'envoyer l'argent à une liste de compte mobile money <br>
                                        Header : x-api-key:[PAYOUT_API_KEY] <br>
                                        params : devise => [CDF|USD], montant => (minimum 500 CDF ou 0.5 USD), telephone[]
                                        => (un tableau de numéros auquels envoyé l'argent. ex.
                                        [0991234567,0811234567,0851234567] )
                                    </small>
                                </div>
                                <small>
                                    Requ&ecirc;te PHP Curl
                                </small>

                                <pre class="text-dark" style="font-size: 12px">
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '{{ route('payout.transfertV1') }});
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'x-api-key: '. PAYOUT_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = http_build_query([
    'montant' => '600',
    'devise' => 'CDF',
    'telephone' => ['0991234567', '0811234567'],
    'date_denvoi' => '2024/06/13 16:41' // optionnel ! si vous voulez planifier l'envoi de l'argent à une date précise, ajoutez ce parametre, au cas contraire, supprimer le dans votre requ&ecirc;te.
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
</pre>
                                <small>Réponse :</small>
                                <div id="jsonsendtrans"></div>
                            </blockquote>


                            <h6 class="text-danger font-italic font-weight-bold">Afficher le status d'une transaction
                            </h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <small>/GET : {{ route('payout.statustransfertV1', 'TRANS_ID') }}<br>
                                        # Affiche le status d'un transfert d'argent : EN ATTENTE, TRAITÉE, REJETÉE <br>
                                        Header : x-api-key:[PAYOUT_API_KEY]
                                    </small>
                                </div>
                                <small>
                                    Requ&ecirc;te PHP Curl
                                </small>

                                <pre class="text-dark" style="font-size: 12px">
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => '{{ route('payout.statustransfertV1', 'TRANS_ID') }}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'x-api-key: '. PAYOUT_API_KEY
    ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
</pre>
                                <small>Réponse :</small>
                                <div id="jsongettransstate"></div>
                            </blockquote>

                            <h6 class="text-danger font-italic font-weight-bold">Supprimer d'une transaction
                            </h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <small>/DELETE : {{ route('payout.deltransfertV1', 'TRANS_ID') }}<br>
                                        # Supprime une transation <br>
                                        <span class="text-danger">Seules les transactions 'EN ATTENTE' peuvent être
                                            supprimées.</span> <br>
                                        Header : x-api-key:[PAYOUT_API_KEY]
                                    </small>
                                </div>
                                <small>
                                    Requ&ecirc;te PHP Curl
                                </small>

                                <pre class="text-dark" style="font-size: 12px">
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => '{{ route('payout.deltransfertV1', 'TRANS_ID') }}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'x-api-key: '. PAYOUT_API_KEY
    ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
</pre>
                                <small>Réponse :</small>
                                <div id="jsontransdel"></div>
                            </blockquote>


                        </div>
                        <div class="card-footer">
                            <p>Télécharger le projet laravel avec modèle d'intégration de l'API. </p>
                            <a href="{{ asset('GoPAY_v2.zip') }}" class="btn btn-sm btn-dark mb-5">
                                <i class="fa fa-download">Télécharger</i>
                            </a>

                            <p>Télécharger l'application Android GoPAY </p>
                            <a href="{{ asset('GoPAY.apk') }}" class="btn btn-sm btn-dark mb-5">
                                <i class="fa fa-download">Télécharger</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    <div class="modal fade" id="mdldis" tabindex="-1" aria-labelledby="mdldis" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="exampleModalLabel" actiontitle></h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="was-validated" id="f-dis" action="#">
                    <div class="modal-body text-danger">
                        <h3 actionmsg></h3>
                        <div id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa fa-times"></i>
                            <span actiontitle></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdlrev" tabindex="-1" aria-labelledby="mdlrev" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="exampleModalLabel">Révoquer</h5>
                    <i class="fa fa-times text-muted fa-2x" data-dismiss="modal" style="cursor: pointer"></i>
                </div>
                <form class="was-validated" id="f-rev" action="#">
                    <div class="modal-body text-danger">
                        <h3>
                            Votre clé PAYOUT sera supprimée et remplacée par une nouvelle clé.
                        </h3>

                        <div id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-seconday btn-sm" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fa fa-times"></i>
                            Révoquer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js-code')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script src=" https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.css "
        rel="stylesheet">
    <script>
        $(function() {

            try {
                $('#jsonbalance').jsonViewer({
                    "success": true,
                    "message": "BALANCE",
                    "data": {
                        "CDF": "250000.63",
                        "USD": "3600.12"
                    }
                });
                $('#jsongettrans').jsonViewer({
                    "success": true,
                    "message": "DEMANDES DE TRANSFERT",
                    "data": {
                        "current_page": 1,
                        "data": [{
                                "id": 28,
                                "trans_id": "CASH.OUT-0017.12319.72864",
                                "montant": "15 000.00 CDF",
                                "au_numero": "+24399xxxx",
                                "status": "TRAITÉE",
                                "note_validation": "DEMAMDE TRAITEE AVEC SUCCESS : [TRAITÉE]",
                                "date": "13-06-2024 11:56:23",
                                "date_denvoi": "13-06-2024 11:56:23",
                                "date_validation": "13-07-2024 11:56:23"
                            },
                            {
                                "id": 27,
                                "trans_id": "CASH.OUT-0016.57179.47873",
                                "montant": "20 000.00 CDF",
                                "au_numero": "+24399xxxx",
                                "status": "REJETÉE",
                                "note_validation": "DEMAMDE TRAITEE AVEC SUCCESS : [REJETÉE]",
                                "date": "14-06-2024 01:56:14",
                                "date_denvoi": "17-06-2024 11:56:23",
                                "date_validation": "17-06-2024 11:56:23"
                            },
                        ],
                        "first_page_url": "{{ route('payout.transfertV1', ['page' => 1]) }}",
                        "from": 16,
                        "last_page": 3,
                        "last_page_url": "{{ route('payout.transfertV1', ['page' => 3]) }}",
                        "links": [{
                                "url": "{{ route('payout.transfertV1', ['page' => 1]) }}",
                                "label": "&laquo; Previous",
                                "active": true
                            },
                            {
                                "url": "{{ route('payout.transfertV1', ['page' => 1]) }}",
                                "label": "1",
                                "active": false
                            },
                        ],
                        "next_page_url": "{{ route('payout.transfertV1', ['page' => 3]) }}",
                        "path": "{{ route('payout.transfertV1') }}",
                        "per_page": 15,
                        "prev_page_url": "{{ route('payout.transfertV1', ['page' => 1]) }}",
                        "to": 30,
                        "total": 32
                    }
                });

                $('#jsonsendtrans').jsonViewer({
                    "success": true,
                    "message": "Votre transfert sera traité à la date : 2024/06/13 16:41. Merci.",
                    "data": null
                });

                $('#jsongettransstate').jsonViewer({
                    "success": true,
                    "message": "Transaction CASH.OUT-0001.66331.86179",
                    "data": {
                        "id": 12,
                        "trans_id": "CASH.OUT-0001.66331.86179",
                        "montant": "5.00 USD",
                        "au_numero": "+2433991234567",
                        "status": "EN ATTENTE",
                        "note_validation": null,
                        "date": "13-08-2023 14:58:51",
                        "date_denvoi": "13-06-2024 11:56:23",
                        "date_validation": null
                    }
                });
                $('#jsontransdel').jsonViewer({
                    "success": true,
                    "message": "La transaction CASH.OUT-0001.66331.86179 a été supprimée.",
                    "data": null
                });
            } catch (error) {}

            var a = $('[baction]').val().split('.')[1];
            if (a == 0) {
                $('[actionmsg]').html(
                    'Votre clé PAYOUT sera desactivée et vous ne pouvez plus effectuer les transactions PayOut.'
                );
                $('[actiontitle]').html('Desactiver');
            } else {
                $('[actionmsg]').html(
                    'Votre clé PAYOUT sera activée et vous pouvez effectuer les transactions.');
                $('[actiontitle]').html('Activer');
            }


            $('#f-rev').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                rep = $('#rep', form);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('marchand.api.revoquepayout') }}',
                    type: 'POST',
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass(
                                    'alert alert-success')
                                .slideDown();
                            $('[key]').html(res.data.key);

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

            $('#f-dis').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                var iclass = btn.find('i').attr('class');
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                rep = $('#rep', form);
                rep.slideUp();
                var v = $('[baction]').val().split('.');
                var data = {
                    'id': v[0],
                    'active': v[1]
                }

                $.ajax({
                    url: '{{ route('marchand.api.apikeys') }}',
                    type: 'POST',
                    data: data,
                    timeout: 20000,
                    success: function(res) {
                        if (res.success == true) {
                            rep.html(res.message).removeClass().addClass(
                                    'alert alert-success')
                                .slideDown();
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
                        rep.removeClass().addClass('alert alert-danger').html(mess)
                            .slideDown();
                    }

                }).always(function(s) {
                    btn.attr('disabled', false).find('i').removeClass().addClass(iclass);
                });
            });
        })
    </script>
@endsection
