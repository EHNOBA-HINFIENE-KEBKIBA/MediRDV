<?php
class EtablissementControleur extends Controleur {

    public function listePublique() {
        $etablissementModel = new Etablissement();
        $villeModel = new Ville();

        $filtres = [
            'type'  => $_GET['type'] ?? null,
            'ville' => $_GET['ville'] ?? null,
        ];
        $etablissements = $etablissementModel->rechercherAvecServices($filtres);
        $types = $etablissementModel->types();
        $villes = $villeModel->tous();

        $this->afficherVue('etablissements_public', [
            'titre'          => 'Établissements de santé',
            'etablissements' => $etablissements,
            'types'          => $types,
            'villes'         => $villes,
            'filtres'        => $filtres
        ]);
    }
}