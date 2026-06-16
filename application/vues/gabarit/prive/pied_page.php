</div> <!-- Fin main-content -->

<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-start">
                <small class="text-muted">
                    © <?= date('Y') ?> MediRDV - Tous droits réservés
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- Bouton retour haut -->
<button id="btnTop"
        class="btn btn-primary rounded-circle shadow"
        style="position:fixed;bottom:20px;right:20px;display:none;z-index:9999;">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Toast notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">

    <?php if(isset($_SESSION['success'])): ?>
        <div class="toast align-items-center text-bg-success border-0 show">
            <div class="d-flex">
                <div class="toast-body">
                    <?= $_SESSION['success'] ?>
                </div>
                <button class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="toast align-items-center text-bg-danger border-0 show">
            <div class="d-flex">
                <div class="toast-body">
                    <?= $_SESSION['error'] ?>
                </div>
                <button class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- QR Code -->
<script src="<?= BASE_URL ?>/public/assets/js/qrcode.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    // Menu actif
    const currentUrl = window.location.pathname;

    document.querySelectorAll('.sidebar .nav-link').forEach(link => {

        if (currentUrl.startsWith(link.getAttribute('href'))) {
            link.classList.add('active');
        }

    });

    // Sidebar mobile
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (btnToggle && sidebar) {

        btnToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });

        if(mainContent){
            mainContent.addEventListener('click', function () {

                if (window.innerWidth <= 768 &&
                    sidebar.classList.contains('show')) {

                    sidebar.classList.remove('show');

                }

            });
        }

        document.addEventListener('keydown', function(e){

            if(e.key === 'Escape'){
                sidebar.classList.remove('show');
            }

        });

    }

    // Retour haut
    const btnTop = document.getElementById('btnTop');

    window.addEventListener('scroll', function(){

        if(window.scrollY > 300){
            btnTop.style.display = 'block';
        }else{
            btnTop.style.display = 'none';
        }

    });

    btnTop.addEventListener('click', function(){

        window.scrollTo({
            top:0,
            behavior:'smooth'
        });

    });

});

// Confirmation suppression

document.querySelectorAll('.btn-delete').forEach(btn => {

    btn.addEventListener('click', function(e){

        if(!confirm('Confirmer la suppression ?')){
            e.preventDefault();
        }

    });

});

// Gestion erreurs JS

window.onerror = function(message, source, line){

    console.error(
        'Erreur JS:',
        message,
        source,
        line
    );

};

</script>
<script src="<?= BASE_URL ?>/public/assets/js/qrcode.min.js"></script>

</body>
</html>