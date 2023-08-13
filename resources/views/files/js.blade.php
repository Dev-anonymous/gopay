@auth
    {{--
<div class="modal fade" id="mdl-logout" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                <b>Vous allez être déconnecter !</b>
            </div>
            <form id="f-add" class="was-validated">
                <div class="modal-body">
                    <p class="text-danger">
                        <b>Vous vous êtes déconnecter sur un autre périphérique, veuillez vous reconnecter ! </b>
                    </p>
                    <p>
                        <button class="btn btn-danger oklogout" type="button">
                            <i class="fa fa-check-circle"></i> D'accord
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endauth

{{-- <script src="{{ asset('js/chart.min.js') }}"></script> --}}
<script src="{{ asset('js/mdb.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
{{-- <script src="{{ asset('js/admin.js') }}"></script> --}}

<script type="text/javascript">
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

{{-- <script src='https://zbot.gooomart.com/zbot/QWtjeGRsM0tPK0xKSlZOU1FLWUVIZz09' async></script> --}}

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
                $('#mdl-logout').modal('show');
            }
        })
    }
    ping();
    @endauth

    $('.oklogout, [logout]').click(function() {
        var btn = $(this);
        if (this.tagName == 'A') {
            btn.html('<span class="spinner-border spinner-border-sm text-dark"></span>');
        } else {
            btn.find('i').removeClass().addClass('spinner-border spinner-border-sm text-dark');
            btn.attr('disabled', true);
        }
        $.post('{{ route('logout.web') }}', function() {
            location.reload();
        })
    })
</script>

@include('files.pwa-js')
