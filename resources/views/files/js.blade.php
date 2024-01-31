{{-- <script src="{{ asset('js/chart.min.js') }}"></script> --}}
<script src="{{ asset('js/mdb.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
{{-- <script src="{{ asset('js/admin.js') }}"></script> --}}

<script type="text/javascript">
    $(window).on('load', function() {
        $('.loader').fadeOut();
    });

    const sidenav = document.getElementById('app-sidebar');
    const instance = mdb.Sidenav.getInstance(sidenav);
    let innerWidth = null;
    const setMode = (e) => {
        try {
            if (window.innerWidth === innerWidth) {
                return;
            }
            innerWidth = window.innerWidth;
            if (window.innerWidth < 660) {
                instance.changeMode('over');
                instance.hide();
            } else {
                instance.changeMode('side');
                instance.show();
            }
        } catch (error) {

        }
    };
    setMode();
    window.addEventListener('resize', setMode);
</script>

<script src='https://zbot.gooomart.com/zbot/QWtjeGRsM0tPK0xKSlZOU1FLWUVIZz09' async></script>

<script>
    @if (!Auth::check())
        localStorage.setItem('_token', '')
    @endif
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + localStorage.getItem('_token'),
            'Accept': 'application/json'
        }
    });

    @auth

    function ping() {
        $.ajax({
            url: '{{ route('ping') }}'
        }).always(function(a) {
            if (401 == a.status) {
                $.post('{{ route('logout.web') }}', function() {
                    location.reload();
                })
                alert('Vous vous êtes déconnecter sur un autre périphérique, veuillez vous reconnecter !')
            }
        })
    }
    ping();
    @endauth
</script>

@include('files.pwa-js')
