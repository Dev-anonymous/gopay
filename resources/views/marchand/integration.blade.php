@extends('layouts.main')
@section('title', 'Intégration API')

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
                        <h3 class="font-weight-bold">INTEGRATION API</h3>
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
                                            <th colspan="3">VOS CLES API</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $apikeys = auth()
                                            ->user()
                                            ->apikeys()
                                            ->get();
                                    @endphp
                                    <tbody>
                                        @foreach ($apikeys as $el)
                                            <tr>
                                                <td>CLEF {{ strtoupper($el->type) }}</td>
                                                <td>{{ $el->key }}</td>
                                                <td class="text-right">
                                                    @if ($el->active)
                                                        <span class="badge bg-success p-2" style="cursor: pointer"
                                                            data-toggle="tooltip"
                                                            title="La clef est prête à être utilisée dans vos projet en mode production.">
                                                            <i class="fa fa-check-circle"></i> ACTIVE
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger p-2" style="cursor: pointer"
                                                            data-toggle="tooltip"
                                                            title="La clef est désactivée, elle ne peut être utilisée.">
                                                            <i class="fa fa-ban"></i> NON ACTIVE
                                                        </span>
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
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="font-weight-bold">Comment intégrer {{ config('app.name') }} ?</h6>
                        </div>
                        <div class="card-body">
                            <p>
                                Vous pouvez intégrer facilement l'API à votre site web, application mobile (Android, iOS) en
                                quelques étapes.
                            </p>
                            <h6 class="text-danger font-italic font-weight-bold">Etape 1 : Initier un paiement</h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <i>/POST : {{ route('pay.init') }}</i> <br>
                                    <i>Header : x-api-key:[API_KEY]</i> <br>
                                    <i>params : devise [CDF|USD], montant, telephone [numéro telephone du client à utiliser
                                        pour le paiement]</i>
                                </div>
                                <p>Initie une transaction pour le compte du marchand.</p>
                                <P>
                                    Reponse de la requête :
                                </P>
                                <pre>
{
    success:true,
    message:"Transaction initialisée avec succès. ...",
    data: {
        ref:"REF-001"
    }
}
</pre>
                            </blockquote>

                            <h6 class="text-danger font-italic font-weight-bold">Etape 2 : Vérifier un paiement</h6>
                            <blockquote class="blockquote bg-grey">
                                <div class="small mb-2">
                                    <i>/POST : {{ route('pay.check') }}/[REF]</i> <br>
                                    <i>Header : x-api-key:[API_KEY]</i>
                                </div>
                                <p>Vérifier l'état de la transaction.</p>
                                <P>
                                    Reponse de la requête :
                                </P>
                                <pre>
{
    success:true,
    message:"Votre transaction est effectuée avec succès.",
}
</pre>
                            </blockquote>
                        </div>
                        <div class="card-footer">
                            <p>Télécharger le projet laravel avec modèle d’intégration de l’API. </p>
                            <a href="" class="btn btn-sm btn-dark mb-5">
                                <i class="fa fa-download">Télécharger</i>
                            </a>
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

        })
    </script>
@endsection