<footer class="pt-5 pb-3">

<div class="container">

<div class="row">

<!-- A PROPOS -->
<div class="col-lg-4 col-md-6 mb-4">

<h4 class="fw-bold mb-3">
<i class="bi bi-heart-pulse-fill text-primary"></i>
MediRDV
</h4>

<p>
MediRDV est une plateforme moderne de prise de rendez-vous médicaux,
de téléconsultation et de gestion des établissements de santé.
</p>

</div>

<!-- LIENS RAPIDES -->
<div class="col-lg-2 col-md-6 mb-4">

<h5 class="fw-bold mb-3">
Liens rapides
</h5>

<ul class="list-unstyled">

<li>
<a href="<?= BASE_URL ?>">Accueil</a>
</li>

<li>
<a href="<?= BASE_URL ?>/a-propos">À propos</a>
</li>

<li>
<a href="<?= BASE_URL ?>/services">Services</a>
</li>

<li>
<a href="<?= BASE_URL ?>/medecins">Médecins</a>
</li>

<li>
<a href="<?= BASE_URL ?>/contact">Contact</a>
</li>

</ul>

</div>

<!-- SERVICES -->
<div class="col-lg-3 col-md-6 mb-4">

<h5 class="fw-bold mb-3">
Nos Services
</h5>

<ul class="list-unstyled">

<li>Prise de rendez-vous</li>
<li>Téléconsultation</li>
<li>Paiement en ligne</li>
<li>QR Code médical</li>
<li>Notifications SMS</li>
<li>Dossier patient</li>

</ul>

</div>

<!-- CONTACT -->
<div class="col-lg-3 col-md-6 mb-4">

<h5 class="fw-bold mb-3">
Contact
</h5>

<p>
<i class="bi bi-telephone-fill"></i>
+235 XX XX XX XX
</p>

<p>
<i class="bi bi-envelope-fill"></i>
contact@medirdv.com
</p>

<p>
<i class="bi bi-geo-alt-fill"></i>
N'Djamena, Tchad
</p>

<div class="d-flex gap-2">

<a href="#" class="social-link">
<i class="bi bi-facebook"></i>
</a>

<a href="#" class="social-link">
<i class="bi bi-whatsapp"></i>
</a>

<a href="#" class="social-link">
<i class="bi bi-linkedin"></i>
</a>

<a href="#" class="social-link">
<i class="bi bi-youtube"></i>
</a>

</div>

</div>

</div>

<hr>

<!-- LIENS LEGAUX -->

<div class="text-center small mt-4">

<a href="<?= BASE_URL ?>/mentions-legales">
Mentions légales
</a>

|

<a href="<?= BASE_URL ?>/confidentialite">
Politique de confidentialité
</a>

|

<a href="<?= BASE_URL ?>/conditions">
Conditions d'utilisation
</a>

|

<a href="<?= BASE_URL ?>/cookies">
Politique des cookies
</a>
 
<a href="<?= BASE_URL ?>/plan-site">Plan du site</a>

|

<a href="<?= BASE_URL ?>/centre-aide">Centre d'aide</a>
</div>

</div>

</div>

<div class="text-center">

<p class="mb-0">

© <?= date('Y') ?>

<strong>MediRDV</strong>

Tous droits réservés.

</p>

<p class="small text-muted mt-2">

Plateforme médicale sécurisée de gestion des rendez-vous,
téléconsultations et établissements de santé.

</p>

</div>

</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

window.addEventListener('scroll',()=>{

const navbar=document.querySelector('.navbar');

if(window.scrollY>50){

navbar.classList.add('scrolled');

}else{

navbar.classList.remove('scrolled');

}

});

</script>

</body>
</html>