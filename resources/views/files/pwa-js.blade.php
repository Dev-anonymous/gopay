{{-- @if (strpos(request()->url(), 'offline') === false)
    <div class="d-flex align-items-center justify-content-center" offline-div>
        <h4 class="text-white font-weight-bold" offline style="display: none !important;">Vous n'êtes pas connecté
        </h4>
    </div>
    <div class="w-100 text-center p-2 fixed-bottom" install-banner style="display: none !important;">
        <p click-zone class="text-white font-weight-bold">Installer l'application {{ config('app.name') }} sur votre
            bureau ?
        </p>
        <div class="d-flex justify-content-center">
            <button class="btn btn-lg btn-outline-light mr-2 btn-noinstall">Non</button>
            <button class="btn btn-lg  btn-warning ml-2 btn-install">Oui</button>
        </div>
    </div>
@endif
<script>
    function updateOnlineStatus(event) {
        let div = $('div[offline-div]');
        let el = $('[offline]');
        if (navigator.onLine) {
            div.attr('style', 'display: none;');
            el.fadeOut();
        } else {
            el.fadeIn();
            div.attr('style',
                'position: fixed;bottom: 0px !important; left:0; right:0;z-index: 999999;background: rgba(255, 0, 0, .5);padding: 10px;'
            );
        }
    }
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline',
        updateOnlineStatus);
    updateOnlineStatus();

    var beforeInstallPrompt = null;
    window.addEventListener("beforeinstallprompt", event => {
        beforeInstallPrompt = event;
    });

    var no = localStorage.getItem('noinstall');
    no = Number(no);
    var ibanner = $('[install-banner]');
    $('.btn-noinstall,[click-zone]').click(function() {
        ibanner.slideUp();
        localStorage.setItem('noinstall', no + 1);
    });
    var canshow = true;
    $('.btn-install').click(function() {
        ibanner.slideUp();
        if (beforeInstallPrompt) {
            beforeInstallPrompt.prompt();
        } else {
            canshow = false;
        }
    });

    if (!window.matchMedia('(display-mode: standalone)').matches) {
        setTimeout(() => {
            if (canshow) {
                if (no < 3) {
                    ibanner.slideDown();
                }
            }
        }, 3000);
    }
</script> --}}
