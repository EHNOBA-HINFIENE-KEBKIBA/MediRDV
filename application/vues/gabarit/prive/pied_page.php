</div> <!-- fin main-content -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour la sidebar active et le bouton toggle mobile -->
<script>
    // Mettre en surbrillance le lien actif
    const currentUrl = window.location.pathname;
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        if (currentUrl.startsWith(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    // Gestion du bouton hamburger pour la sidebar sur mobile
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebar');
    if (btnToggle && sidebar) {
        btnToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        // Fermer la sidebar si on clique en dehors (sur le contenu principal)
        document.getElementById('mainContent').addEventListener('click', function() {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Réinitialiser l'état de la sidebar au redimensionnement
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
        }
    });
</script>
<script src="<?= BASE_URL ?>/public/assets/js/qrcode.min.js"></script>
</body>
</html>