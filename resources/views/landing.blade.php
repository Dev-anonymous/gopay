 <html lang="en">

 <head>
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="format-detection" content="telephone=no">
     <meta name="apple-mobile-web-app-capable" content="yes">
     <title>Accueil - {{ config('app.name') }} </title>
     <link rel='shortcut icon' type='image/x-icon' href="{{ asset('img/icon.png') }}" />

     <link rel="stylesheet" href="{{ asset('css/fontawesome-6.4.2/css/all.css') }}">
     <link rel="stylesheet" href="{{ asset('css/bs4.min.css') }}" />
     <link rel="stylesheet" href="{{ asset('css/mdb4.min.css') }}" />
     <style>
         html,
         body,
         header,
         .intro-2 {
             height: 100%;
         }

         @media (max-width: 740px) {

             html,
             body,
             header,
             .intro-2 {
                 height: 900px;
             }
         }

         @media (min-width: 800px) and (max-width: 850px) {

             html,
             body,
             header,
             .intro-2 {
                 height: 900px;
             }
         }

         .creative-lp .rgba-gradient .mask {
             background: rgba(33, 33, 33, 0.8);
         }
     </style>
 </head>

 <body class="creative-lp">
     <header>
         <x-nav-app />

         <section class="view intro-2" style="background-image: url('{{ asset('img/d.png') }}');">
             <div class="mask">
                 <div class="container h-100 d-flex justify-content-center align-items-center">
                     <div class="row flex-center pt-5 mt-3">
                         <div class="col-md-12 col-lg-6 text-center text-md-left text-white">
                             <div class="dark-grey-text p-3 mt-5"
                                 style="background: rgba(0,0,0,.25); border-radius: 10px">
                                 <h1 class="display-4 mt-5 font-weight-bold wow fadeIn" data-wow-delay="0.3s">
                                     <strong class="text-white">
                                         {{ config('app.name') }}
                                     </strong>
                                     <strong style="font-size: 10px"
                                         class="text-dark bg-warning rounded-pill p-1">Powered By Gooomart</strong>
                                 </h1>
                                 <hr class="hr-light wow fadeIn" data-wow-delay="0.3s">
                                 <h5 class="text-white wow fadeIn font-weight-bold" data-wow-delay="0.3s">
                                     Nous travaillons durement pour vous faciliter et sécuriser vos paiements en ligne.
                                     Maximiser vos chiffres d'affaires en optant pour les paiements électroniques grâce
                                     à
                                     {{ config('app.name') }} ! c'est
                                     <span class="font-weight-bold font-italic"
                                         style="color: #050101; text-shadow: 1px 1px #fb3">
                                         simple, rapide et sécurisé !
                                     </span>
                                 </h5>
                                 <br>
                                 <a href="#contact" id="a-contact"
                                     class="btn btn-white rounded-lg font-weight-bold ml-lg-0 wow fadeIn"
                                     data-wow-delay="0.3s">Contact</a>
                                 <a href="{{ route('app.login') }}"
                                     class="btn btn-dark rounded-lg font-weight-bold wow fadeIn"
                                     data-wow-delay="0.3s">Commencer
                                 </a>
                             </div>
                         </div>

                         <div class="col-md-12 col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                             <img src="{{ asset('img/1.png') }}" alt="" class="img-fluid">
                         </div>
                     </div>
                 </div>
             </div>
         </section>
     </header>
     <main>
         <div class="container">
             <section id="features" class="mb-5 pb-4">
                 <h3 class="text-center title my-5 font-weight-bold wow fadeIn" data-wow-delay="0.2s">
                     <strong class="text-dark">Fonctionnalités {{ config('app.name') }}</strong>
                 </h3>
                 <p class="text-center w-responsive mx-auto my-5 text-dark font-weight-bold wow fadeIn"
                     data-wow-delay="0.2s">
                     Vous avez opté pour les paiements électroniques ? {{ config('app.name') }} vous offre la
                     possibilité
                     de recevoir de
                     recevoir le paiement sur votre plateforme.
                 </p>
                 <div class="row features-small wow fadeIn text-dark" data-wow-delay="0.4s">
                     <div class="col-lg-4 col-md-12">
                         <div class="row mb-3">
                             <div class="col-2">
                                 <i class="fas fa-globe-africa text-dark fa-2x"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark ">Intégration WEB</h5>
                                 <p class="text-dark">
                                     Intégrer facilement l'API {{ config('app.name') }} à vos sites web en toute
                                     simplicité.
                                 </p>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-2">
                                 <i class="fas fa-tablet-alt fa-2x text-warning"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark">Intégration Android & iOS</h5>
                                 <p class="text-dark">
                                     L'API peut également être intégrée à vos application Android et iOS.
                                 </p>
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-2">
                                 <i class="fas fa-cogs fa-2x text-primary"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark">Configuration facile</h5>
                                 <p class="text-dark">
                                     Presque aucune configuration de l'API, nous vous avons faciliter votre
                                     configuration.
                                 </p>
                             </div>
                         </div>
                     </div>

                     <div class="col-lg-4 col-md-12 mb-1 text-center text-md-left">
                         <img src="{{ asset('img/1.png') }}" alt="" class="z-depth-0 img-fluid">
                     </div>

                     <div class="col-lg-4 col-md-12">
                         <div class="row mb-3">
                             <div class="col-2">
                                 <i class="fas fa-lock text-success fa-2x"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark">Paiements sécurisés</h5>
                                 <p class="text-dark">
                                     {{ config('app.name') }} vous garentie la sécurité de toutes vos transactions.
                                 </p>
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-2">
                                 <i class="fas fa-users fa-2x text-info"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark">Support technique</h5>
                                 <p class="text-dark">
                                     Un support technique à votre disposition.
                                 </p>
                             </div>
                         </div>

                         <div class="row">
                             <div class="col-2">
                                 <i class="far fa-credit-card fa-2x text-danger"></i>
                             </div>
                             <div class="col-10 mb-2">
                                 <h5 class="font-weight-bold text-dark">Mobiles Money & Cartes de crédit</h5>
                                 <p class="text-dark">
                                     {{ config('app.name') }} prend en charge les paiements avec <span
                                         class="font-weight-bold text-danger">tous les opérateurs
                                         mobiles de la RDC 😊</span>.
                                 </p>
                             </div>
                         </div>
                     </div>
                 </div>
             </section>
         </div>
         <div class="streak streak-photo streak-long-2 rgba-gradient"
             style="background-image: url('https://mdbootstrap.com/img/Photos/Horizontal/Things/full%20page/img%20%287%29.jpg');">
             <div class="flex-center mask">
                 <div class="container">
                     <div class="row text-center">
                         <div class="col-md-4 mb-2">
                             <h1 class="white-text mb-1 font-weight-bold">+500</h1>
                             <p class="white-text font-weight-bold">Transactions traitées</p>
                         </div>
                         <div class="col-md-4 mb-2">
                             <h1 class="white-text mb-1 font-weight-bold">+1200</h1>
                             <p class="white-text font-weight-bold">Requêtes API</p>
                         </div>
                         <div class="col-md-4 mb-2">
                             <h1 class="white-text mb-1 font-weight-bold">+15</h1>
                             <p class="white-text font-weight-bold">Marchands</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="container bg-white rounded shadow-lg" id="contact">
             <section id="contact" class="mb-5 ">
                 <h3 class="text-center text-dark mt-5 pt-3 dark-grey-text font-weight-bold wow fadeIn"
                     data-wow-delay="0.2s">
                     <strong>Nous contacter</strong>
                 </h3>
                 <p class="text-center text-dark h2 mb-3 h5 w-responsive mx-auto wow fadeIn" data-wow-delay="0.2s">
                     Besoin d'un reinseignement ? veuillez laisser votre message, suggestion ou préoccupation et nous
                     reviendrons à vous si nécessaire.
                 </p>
                 <div class="row wow fadeIn p-5" data-wow-delay="0.4s">
                     <div class="col-md-8 col-lg-9">
                         <form action="#" id="f-cont">
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="md-form md-outline mb-4">
                                         <input type="text" id="nom" required name="nom"
                                             class="form-control" />
                                         <label for="nom">Nom</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-6">
                                     <div class="md-form md-outline mb-4">
                                         <input id="femail" required name="email" type="email"
                                             class="form-control" />
                                         <label for="femail">Email</label>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="md-form md-outline mb-4">
                                         <input id="phone" type="text" name="telephone" required
                                             class="form-control" />
                                         <label for="phone">Telephone</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="md-form md-outline mb-4">
                                         <input required id="fsub" type="text" name="sujet"
                                             class="form-control" />
                                         <label for="fsub">Sujet</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="md-form md-outline mb-4">
                                         <textarea name="message" id="fmess" required class="form-control"></textarea>
                                         <label for="fmess">Message</label>
                                     </div>
                                 </div>
                             </div>
                             <div id="rep"></div>
                             <div class="text-center text-md-left mb-5 mt-4">
                                 <button type="submit" class="btn btn-dark btn-sm rounded-lg font-weight-bold">
                                     <i class="fas fa-envelope"></i> Envoyer
                                 </button>
                             </div>
                         </form>
                     </div>
                     <div class="col-md-4 col-lg-3">
                         <ul class="text-center list-unstyled float-md-right">
                             <li>
                                 <i class="fas fa-map-marker-alt fa-2x text-dark"></i>
                                 <p class="text-muted font-weight-bold">Lubumbashi, HK, DRC</p>
                             </li>
                             <li>
                                 <i class="fas fa-phone fa-2x text-dark"></i>
                                 <p class="text-muted font-weight-bold">
                                     <a target="_blank" class="text-muted font-weight-bold"
                                         href="https://wa.me/243998333702">
                                         +243998333702
                                     </a>
                                 </p>
                             </li>
                             <li>
                                 <i class="fas fa-envelope fa-2x text-dark"></i>
                                 <p>
                                     <a class="text-muted font-weight-bold" href="mailto:contact@gooomart.com">
                                         contact@gooomart.com
                                     </a>
                                 </p>
                             </li>
                         </ul>
                     </div>
                 </div>
             </section>
         </div>
     </main>

     <footer class="text-center text-md-left pt-3" style="background: #050101 !important;color: white;">
         <div class="container mt-5 mb-4 text-center text-md-left">
             <div class="row mt-3">
                 <div class="col-md-4 mb-4">
                     <h6 class="spacing font-weight-bold">
                         <strong>
                             {{ config('app.name') }}
                         </strong>
                     </h6>
                     <hr class="pink accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                     <p>
                         Nous travaillons durement pour vous faciliter et sécuriser vos paiements en ligne.
                         Maximiser vos chiffres d'affaires en optant pour les paiements électroniques
                         {{ config('app.name') }} ! c'est simple, rapide et sécurisé !
                     </p>
                 </div>
                 <div class="col-md-4 mb-4">
                     <h6 class="spacing font-weight-bold">
                         <strong>Contact</strong>
                     </h6>
                     <hr class="pink accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                     <p>
                         <a target="_blank" class="text-white font-weight-bold" href="https://wa.me/243998333702">
                             <i class="fa fa-phone-flip"></i>
                             Contact : +243998333702
                         </a>
                     </p>
                     <p>
                         <a class="text-white font-weight-bold">
                             <i class="fa fa-map-marker"></i>
                             Lubumbashi, HK, DRC
                         </a>
                     </p>
                 </div>
                 <div class="col-md-4 mb-4">
                     <h6 class="spacing font-weight-bold">
                         <strong>Liens</strong>
                     </h6>
                     <hr class="pink accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                     <p>
                         <a target="_blank" class="text-white font-weight-bold"
                             href="https://www.gooomart.com?source=gopay">
                             Gooomart
                         </a>
                     </p>
                     <p>
                         <a target="_blank" class="text-white font-weight-bold"
                             href="https://www.zbot.gooomart.com?source=gopay">
                             Zbot
                         </a>
                     </p>
                     <p>
                         <a target="_blank" class="text-white font-weight-bold"
                             href="https://www.control.gooomart.com?source=gopay">
                             Control
                         </a>
                     </p>
                 </div>
             </div>
         </div>

         <div class="footer-copyright py-3 text-center wow fadeIn" data-wow-delay="0.3s"
             style="background: #212121 !important;">
             <div class="container-fluid">
                 <b style="font-size: 12px" class="mr-3">&copy; {{ date('Y') }} {{ config('app.name') }},
                     Powered
                     by
                     <a href="https://www.gooomart.com?source=gopay" class="text-warning"
                         target="_blank">Gooomart</a>
                 </b>
                 |
                 <b style="font-size: 12px" class="ml-3">
                     <a target="_blank" class="text-danger font-weight-bold" href="https://wa.me/243998333702">
                         <i class="fa fa-phone"></i>
                         Contact : +243998333702
                     </a>
                 </b>
             </div>
         </div>

     </footer>

     <script type="text/javascript" src="{{ asset('js/jq.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('js/bs4.min.js') }}"></script>
     <script type="text/javascript" src="{{ asset('js/mdb4.min.js') }}"></script>

     <script>
         new WOW().init();
     </script>
     <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
     <script>
         $('#a-contact').click(function() {
             event.preventDefault();
             $('html, body').animate({
                 scrollTop: $('#contact').offset().top - 100
             }, 1000);
         })
         $(function() {
             $('#phone').mask('000000000000');



             $('#f-cont').submit(function() {
                 event.preventDefault();
                 var form = $(this);
                 var btn = $(':submit', form).attr('disabled', true);
                 var iclass = btn.find('i').attr('class');
                 btn.find('i').removeClass()
                     .addClass('spinner-border spinner-border-sm');
                 var data = form.serialize();
                 data += "&telephone=" + $('#phone').val();
                 rep = $('#rep', form);
                 rep.slideUp();
                 $.ajax({
                     url: '{{ route('feedback') }}',
                     type: 'POST',
                     data: data,
                     timeout: 20000,
                     success: function(res) {
                         if (res.success == true) {
                             rep.html(res.message).removeClass().addClass('alert alert-success')
                                 .slideDown();
                             form[0].reset();
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
     <script src='https://zbot.gooomart.com/zbot/QWtjeGRsM0tPK0xKSlZOU1FLWUVIZz09' async></script>

 </body>

 </html>
