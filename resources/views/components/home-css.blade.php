<style>
    #intro {
        background-image: url('{{ asset('img/bg.jpg') }}');
        height: 105vh;
    }

    /* Height for devices larger than 576px */
    @media (min-width: 992px) {
        #intro {
            margin-top: -58.59px;
        }
    }

    a.nav-link {
        transition: transform .3s;
    }

    a.nav-link.active>span {
        border-bottom: 2px solid red;
        border-radius: 5px;
        padding: 5px 5px;
    }

    a.nav-link:hover {
        transform: scale(.8);
    }
</style>
