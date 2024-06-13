@component('mail::message')
# Demande de réinitialisation du mot de passe : {{ now('Africa/Lubumbashi')->format('d-m-Y H:i:s') }}

{!! nl2br($data->msg) !!}

CET EMAIL EST VALIDE PENDANT 24H.

@component('mail::button', ['url' => $data->url])
Réinitialiser le mot de passe
@endcomponent

SI VOUS N'AVEZ PAS DEMANDER LA REINITIALISATION DE VOTRE MOT DE PASSE, VEUILLEZ CONTACTER LE SERVICE CLIENT EN LIGNE LE PLUS VITE POSSIBLE.<br>
{{ config('app.name') }}
@endcomponent
