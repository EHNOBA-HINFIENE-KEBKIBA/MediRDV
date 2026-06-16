<?php

class AccueilControleur extends Controleur
{
    public function index()
    {
        $statistique = new Statistique();

        $stats = $statistique->getCounts();

        $temoignages = (new Temoignage())->tousActifs();

        $partenaires = (new Partenaire())->tous();

        $this->afficherVue('accueil', [
            'titre' => 'Bienvenue sur MediRDV',
            'stats' => $stats,
            'temoignages' => $temoignages,
            'partenaires' => $partenaires
        ]);
    }

    public function aPropos()
    {
        $this->afficherVue('a_propos', [
            'titre' => 'À propos'
        ]);
    }

    public function services()
    {
        $this->afficherVue('services', [
            'titre' => 'Services'
        ]);
    }

    public function mentionsLegales()
    {
        $this->afficherVue('legales/mentions_legales', [
            'titre' => 'Mentions légales'
        ]);
    }

    public function confidentialite()
    {
        $this->afficherVue('legales/confidentialite', [
            'titre' => 'Politique de confidentialité'
        ]);
    }

    public function conditions()
    {
        $this->afficherVue('legales/conditions', [
            'titre' => "Conditions d'utilisation"
        ]);
    }

    public function cookies()
    {
        $this->afficherVue('legales/cookies', [
            'titre' => 'Politique des cookies'
        ]);
    }

    public function erreur404()
    {
        $this->afficherVue('erreurs/404', [
            'titre' => 'Erreur 404'
        ]);
    }
    public function planSite()
{
    $this->afficherVue('plan_site', [
        'titre' => 'Plan du site'
    ]);
}

public function centreAide()
{
    $this->afficherVue('centre_aide', [
        'titre' => "Centre d'aide"
    ]);
}
}