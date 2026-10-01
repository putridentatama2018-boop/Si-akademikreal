<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

    <div class="container">

        <a class="navbar-brand" href="/si-akademik/public/">
            SI Akademik
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/si-akademik/public/">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/si-akademik/public/mahasiswa">
                        Mahasiswa
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/si-akademik/public/prodi">
                        Prodi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/si-akademik/public/matakuliah">Mata Kuliah</a>
                </li>

            </ul>

            <ul class="navbar-nav align-items-center">

                <li class="nav-item">
                    <a class="btn-logout" href="/si-akademik/public/logout">
                        Logout
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<style>
    .navbar .nav-link {
        position: relative;
        padding-bottom: 8px;
        transition: color 0.3s;
    }

    .navbar .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: #ffffff;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .navbar .nav-link:hover::after {
        width: 60%;
    }

    .navbar .nav-link.active {
        font-weight: 600;
    }

    .navbar .nav-link.active::after {
        width: 60%;
    }

    /* Tombol Logout Merah */
    .navbar .btn-logout {
        display: inline-block;
        background-color: #dc3545;
        color: #ffffff !important;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .navbar .btn-logout:hover {
        background-color: #bb2d3b;
        color: #ffffff !important;
    }

    .navbar .btn-logout:active {
        transform: scale(0.98);
    }
</style>

<script>
    (function() {
        var path = window.location.pathname;
        var links = document.querySelectorAll('.navbar .nav-link');

        links.forEach(function(link) {
            var href = link.getAttribute('href');

            // Exact match untuk Home
            if (href === '/si-akademik/public/' && (path === '/si-akademik/public/' || path === '/si-akademik/public')) {
                link.classList.add('active');
            }
            // Prefix match untuk halaman lain (mahasiswa, prodi, matakuliah, dll)
            else if (href !== '/si-akademik/public/' && path.startsWith(href)) {
                link.classList.add('active');
            }
        });
    })();
</script>