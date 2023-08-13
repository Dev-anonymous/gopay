{{-- <style>
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 16px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
    }

    * {
        -ms-overflow-style: 8px;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.05);
    }
</style> --}}

{{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" /> --}}
{{-- <link
rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap"
/> --}}
<link rel="stylesheet" href="{{ asset('css/fontawesome-6.4.2/css/all.css') }}">
<link rel="stylesheet" href="{{ asset('css/mdb.min.css') }}" />
{{-- <link rel="stylesheet" href="{{ asset('css/admin.css') }}" /> --}}

<style>
    .page-intro {
        background-color: white;
        width: 100vw;
        min-height: 100vh;
    }

    .mdb-page-content {
        padding-left: 240px;
        transition: padding 0.3s linear;
        padding-bottom: 100px;
    }

    #toggler {
        display: none;
    }

    @media (max-width: 660px) {
        .mdb-page-content {
            padding-left: 0px;
        }

        #toggler {
            display: unset;
        }
    }

    .sidenav-link.active[link] {
        font-weight: 900;
        color: white;
        background: #262626;
    }
</style>

<link rel='shortcut icon' type='image/x-icon' href="{{ asset('img/favicon.png') }}" />
{{-- <style>
    .bg-error {
        background: rgba(255, 0, 0, 0.1)
    }

    [no-under]:hover {
        text-decoration: none;
    }

    .ombre:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }
</style> --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
