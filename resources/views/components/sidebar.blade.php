<div id="app-sidebar" class="sidenav bg-white" data-color="dark" data-mode="side" data-hidden="false"
    data-scroll-container="#scrollContainer">
    <div class="mt-4">
        <div id="header-content" class="pl-3 d-flex justify-content-center">
            <img src="{{ asset('img/logo2.png') }}" alt="" class="mb-3" height="70px" width="130px" />
        </div>
        <hr class="mb-0" />
    </div>
    <div id="scrollContainer">
        <ul class="sidenav-menu">
            @if (auth()->user()->user_role == 'admin')
                <li class="sidenav-item">
                    <a class="sidenav-link" link href="{{ route('admin.web.index') }}">
                        <i class="fas fa-gauge pr-3"></i>Dashboard</a>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link" link href="{{ route('admin.web.trans') }}">
                        <i class="fas fa-right-left pr-3"></i>
                        Transactions
                    </a>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fa-solid fa-money-bill-trend-up pr-3"></i>
                        Envoi fonds</a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('admin.web.cashout') }}">
                                <i class="fa-solid fa-money-check-dollar p-1"></i>
                                Demandes de transfert
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fas fa-users-gear pr-3"></i>
                        Marchands
                    </a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('admin.web.merchent') }}">
                                <i class="fa-solid fa-users-between-lines pr-1"></i>
                                Comptes marchands
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fas fa-bars-progress pr-3"></i>
                        Autres
                    </a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('admin.web.feedback') }}">
                                <i class="fas fa-rss pr-1"></i>
                                Feedback
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (auth()->user()->user_role == 'marchand')
                <li class="sidenav-item">
                    <a class="sidenav-link" link href="{{ route('marchand.web.index') }}">
                        <i class="fas fa-gauge pr-3"></i>Dashboard</a>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link" link href="{{ route('marchand.web.trans') }}">
                        <i class="fas fa-right-left pr-3"></i>
                        Transactions
                    </a>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fa-solid fa-money-check pr-3"></i>
                        Accepter les paiements</a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('marchand.web.cashin') }}">
                                <i class="fa-solid fa-check-double p-1"></i>
                                Accepter un paiement
                            </a>
                        </li>
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('marchand.web.lien_pay') }}">
                                <i class="fa-solid fa-money-bill-1 p-1"></i>
                                Lien de paiement
                            </a>
                        </li>
                        {{-- <li class="sidenav-item">
                            <a class="sidenav-link" link href="#">
                                <i class="fa-solid fa-users p-1"></i>
                                Comptes de paiement
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fa-solid fa-money-bill-trend-up pr-3"></i>
                        Envoi fonds</a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('marchand.web.cashout') }}">
                                <i class="fa-solid fa-money-check-dollar p-1"></i>
                                Transfert de fonds
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fas fa-key pr-3"></i>
                        API & Intégration
                    </a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('marchand.web.integration') }}">
                                <i class="fa-solid fa-cogs pr-1"></i>
                                Intégration
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidenav-item">
                    <a class="sidenav-link">
                        <i class="fas fa-user-alt pr-3"></i>
                        Compte
                    </a>
                    <ul class="sidenav-collapse">
                        <li class="sidenav-item">
                            <a class="sidenav-link" link href="{{ route('marchand.web.compte') }}">
                                <i class="fas fa-user-check pr-1"></i>
                                Mon compte
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    {{-- <div class="text-center" style="height: 100px;">
                <hr class="mb-4 mt-0" />
                <p>MDBootstrap.com</p>
            </div> --}}
</div>
<script type="text/javascript">
    (function() {

        document.ready(function() {
            console.log('r');
        })

        const sidenav = document.getElementById('app-sidebar');
        const instance = mdb.Sidenav.getInstance(sidenav);

        let innerWidth = null;

        const setMode = (e) => {
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
        };

        // setMode();
        // window.addEventListener('resize', setMode);
    })
</script>
