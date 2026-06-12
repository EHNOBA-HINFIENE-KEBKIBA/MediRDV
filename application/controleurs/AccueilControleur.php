<?php
class AccueilControleur extends Controleur {

   public function index() {
    $statistique = new Statistique();
    $stats = $statistique->getCounts();
    $temoignages = (new Temoignage())->tousActifs(); // seulement les actifs
    $partenaires = (new Partenaire())->tous();
    $this->afficherVue('accueil', [
        'titre' => 'Bienvenue sur MediRDV',
        'stats' => $stats,
        'temoignages' => $temoignages,
        'partenaires' => $partenaires
    ]);
}

    public function erreur404() {
        $this->afficherVue('erreurs/404', ['titre' => 'Erreur 404']);
    }
    public function aPropos() {
    $this->afficherVue('a_propos', ['titre' => 'À propos']);
}

public function services() {
    $this->afficherVue('services', ['titre' => 'Services']);
}

public function mentionsLegales() {
    $this->afficherVue('mentions_legales', ['titre' => 'Mentions légales']);
}

public function confidentialite() {
    $this->afficherVue('confidentialite', ['titre' => 'Politique de confidentialité']);
}

public function conditions() {
    $this->afficherVue('conditions', ['titre' => 'Conditions d\'utilisation']);
}

public function cookies() {
    $this->afficherVue('cookies', ['titre' => 'Politique de cookies']);
}
}