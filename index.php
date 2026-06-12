<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/configuration/base_de_donnees.php';
require_once __DIR__ . '/configuration/langue.php';
require_once __DIR__ . '/configuration/email.php';
require_once __DIR__ . '/application/helpers/Securite.php';

// Chargement manuel des classes de base
require_once __DIR__ . '/noyau/BaseDeDonnees.php';
require_once __DIR__ . '/noyau/Controleur.php';
require_once __DIR__ . '/noyau/Modele.php';

// Chargement manuel d'AccueilControleur (pour éviter tout problème)
require_once __DIR__ . '/application/controleurs/AccueilControleur.php';

define('BASE_URL', '/MediRDV');

// Autoload pour toutes les autres classes
spl_autoload_register(function ($classe) {
    $dossiers = [
        __DIR__ . '/noyau/',
        __DIR__ . '/application/controleurs/',
        __DIR__ . '/application/modeles/'
    ];
    foreach ($dossiers as $dossier) {
        $fichier = $dossier . $classe . '.php';
        if (file_exists($fichier)) {
            require_once $fichier;
            return;
        }
    }
});

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'accueil';

$routes = [
    'accueil'                                      => ['AccueilControleur', 'index'],
    'connexion'                                    => ['AuthentificationControleur', 'connexion'],
    'traiter-connexion'                            => ['AuthentificationControleur', 'traiterConnexion'],
    'inscription'                                  => ['AuthentificationControleur', 'inscription'],
    'traiter-inscription'                          => ['AuthentificationControleur', 'traiterInscription'],
    'deconnexion'                                  => ['AuthentificationControleur', 'deconnexion'],
    'tableau-bord'                                 => ['TableauBordControleur', 'index'],
    'prendre-rdv'                                  => ['RendezVousControleur', 'rechercher'],
    'prendre-rdv/choisir/{id}'                     => ['RendezVousControleur', 'choisir'],
    'prendre-rdv/reserver'                         => ['RendezVousControleur', 'reserver'],
    'mes-rendezvous'                               => ['RendezVousControleur', 'mesRendezVous'],
    'medecin/agenda'                               => ['MedecinControleur', 'agenda'],
    'medecin/changer-statut'                       => ['MedecinControleur', 'changerStatut'],
    'medecin/disponibilites'                       => ['MedecinControleur', 'disponibilites'],
    'medecin/ajouter-disponibilite'                => ['MedecinControleur', 'ajouterDisponibilite'],
    'medecin/supprimer-disponibilite/{id}'         => ['MedecinControleur', 'supprimerDisponibilite'],
    'admin/etablissements'                         => ['AdminControleur', 'etablissements'],
    'admin/ajouter-etablissement'                  => ['AdminControleur', 'ajouterEtablissement'],
    'admin/enregistrer-ajout-etablissement'        => ['AdminControleur', 'enregistrerAjoutEtablissement'],
    'admin/modifier-etablissement/{id}'            => ['AdminControleur', 'modifierEtablissement'],
    'admin/enregistrer-modification-etablissement' => ['AdminControleur', 'enregistrerModificationEtablissement'],
    'admin/supprimer-etablissement/{id}'           => ['AdminControleur', 'supprimerEtablissement'],
    'admin/utilisateurs'                           => ['AdminControleur', 'utilisateurs'],
    'admin/ajouter-utilisateur'                    => ['AdminControleur', 'ajouterUtilisateur'],
    'admin/enregistrer-ajout-utilisateur'          => ['AdminControleur', 'enregistrerAjoutUtilisateur'],
    'admin/modifier-utilisateur/{id}'              => ['AdminControleur', 'modifierUtilisateur'],
    'admin/enregistrer-modification-utilisateur'   => ['AdminControleur', 'enregistrerModificationUtilisateur'],
    'admin/supprimer-utilisateur/{id}'             => ['AdminControleur', 'supprimerUtilisateur'],
    'admin/services'                               => ['AdminControleur', 'services'],
    'admin/ajouter-service'                        => ['AdminControleur', 'ajouterService'],
    'admin/supprimer-service/{id}'                 => ['AdminControleur', 'supprimerService'],
    'admin/specialites'                            => ['AdminControleur', 'specialites'],
    'admin/ajouter-specialite'                     => ['AdminControleur', 'ajouterSpecialite'],
    'admin/supprimer-specialite/{id}'              => ['AdminControleur', 'supprimerSpecialite'],
    'medecins'                                     => ['MedecinControleur', 'listePublique'],
    'etablissements'                               => ['EtablissementControleur', 'listePublique'],
    'contact'                                      => ['ContactControleur', 'index'],
    'traiter-contact'                              => ['ContactControleur', 'envoyer'],
    'admin/villes'                                 => ['AdminControleur', 'villes'],
    'admin/ajouter-ville'                          => ['AdminControleur', 'ajouterVille'],
    'admin/supprimer-ville/{id}'                   => ['AdminControleur', 'supprimerVille'],
    'teleconsultation/gerer/{id}'                  => ['TeleconsultationControleur', 'gerer'],
    'teleconsultation/rejoindre/{id}'              => ['TeleconsultationControleur', 'rejoindre'],
    'receptionniste/tableau-bord'                  => ['ReceptionnisteControleur', 'tableauBord'],
    'receptionniste/creer-rdv'                     => ['ReceptionnisteControleur', 'creerRendezVous'],
    'receptionniste/enregistrer-rdv'          => ['ReceptionnisteControleur', 'enregistrerRendezVous'],
'receptionniste/modifier-rdv/{id}'        => ['ReceptionnisteControleur', 'modifierRendezVous'],
'receptionniste/enregistrer-modification-rdv' => ['ReceptionnisteControleur', 'enregistrerModificationRendezVous'],
'receptionniste/annuler-rdv/{id}'         => ['ReceptionnisteControleur', 'annulerRendezVous'],
'receptionniste/tableau-bord'    => ['ReceptionnisteControleur', 'tableauBord'], // file d'attente
'receptionniste/accueil'         => ['ReceptionnisteControleur', 'index'],       // nouveau tableau de bord
'receptionniste/creer-rdv'       => ['ReceptionnisteControleur', 'creerRendezVous'],
'paiements'                    => ['PaiementControleur', 'mesPaiements'],
'paiement/payer/{id}'          => ['PaiementControleur', 'payer'],
'paiement/traiter'             => ['PaiementControleur', 'traiterPaiement'],
'admin/paiements'              => ['PaiementControleur', 'listeEtablissement'],  // pour admin/réceptionniste
'admin-etablissement/tableau-bord'      => ['AdminEtablissementControleur', 'index'],
'admin-etablissement/medecins'          => ['AdminEtablissementControleur', 'medecins'],
'admin-etablissement/modifier-medecin/{id}' => ['AdminEtablissementControleur', 'modifierMedecin'],
'admin-etablissement/enregistrer-medecin'   => ['AdminEtablissementControleur', 'enregistrerMedecin'],
'admin-etablissement/receptionnistes'   => ['AdminEtablissementControleur', 'receptionnistes'],
'admin-etablissement/creer-receptionniste' => ['AdminEtablissementControleur', 'creerReceptionniste'],
'admin-etablissement/supprimer-receptionniste/{id}' => ['AdminEtablissementControleur', 'supprimerReceptionniste'],
'admin-etablissement/statistiques'      => ['AdminEtablissementControleur', 'statistiques'],
// Blog public
'blog'                  => ['BlogPublicControleur', 'index'],
'blog/article/{id}'     => ['BlogPublicControleur', 'article'],
// FAQ publique
'faq'                   => ['FaqPublicControleur', 'index'],
// Admin Blog & FAQ
'admin/blog'            => ['BlogFaqControleur', 'blogAdmin'],
'admin/blog/ajouter'    => ['BlogFaqControleur', 'ajouterArticle'],
'admin/blog/modifier/{id}' => ['BlogFaqControleur', 'modifierArticle'],
'admin/blog/supprimer/{id}'=> ['BlogFaqControleur', 'supprimerArticle'],
'admin/faq'             => ['BlogFaqControleur', 'faqAdmin'],
'admin/faq/ajouter'     => ['BlogFaqControleur', 'ajouterFaq'],
'admin/faq/supprimer/{id}' => ['BlogFaqControleur', 'supprimerFaq'],
'profil'                           => ['ProfilControleur', 'index'],
'profil/modifier'                  => ['ProfilControleur', 'modifier'],
'profil/mettre-a-jour'            => ['ProfilControleur', 'mettreAJour'],
'profil/changer-mot-de-passe'     => ['ProfilControleur', 'changerMotDePasse'],
'profil/enregistrer-mot-de-passe' => ['ProfilControleur', 'enregistrerMotDePasse'],
'admin/bloquer-utilisateur/{id}'    => ['AdminControleur', 'bloquerUtilisateur'],
'admin/debloquer-utilisateur/{id}'  => ['AdminControleur', 'debloquerUtilisateur'],
'medecin/patients'                => ['MedecinControleur', 'mesPatients'],
'medecin/sauvegarder-observations' => ['MedecinControleur', 'sauvegarderObservations'],
'medecin/modifier-rdv/{id}'       => ['MedecinControleur', 'modifierRdv'],
'a-propos'  => ['AccueilControleur', 'aPropos'],
'services'  => ['AccueilControleur', 'services'],
'admin/temoignages'               => ['AdminTemoignageControleur', 'index'],
'admin/temoignages/ajouter'       => ['AdminTemoignageControleur', 'ajouter'],
'admin/temoignages/basculer/{id}' => ['AdminTemoignageControleur', 'basculer'],
'admin/temoignages/supprimer/{id}'=> ['AdminTemoignageControleur', 'supprimer'],
'consultation/gerer/{id}'   => ['ConsultationControleur', 'gerer'],
'consultation/voir/{id}'    => ['ConsultationControleur', 'voir'],
'consultation/historique'   => ['ConsultationControleur', 'historique'],
'mentions-legales'   => ['AccueilControleur', 'mentionsLegales'],
'confidentialite'    => ['AccueilControleur', 'confidentialite'],
'conditions'         => ['AccueilControleur', 'conditions'],
'cookies'            => ['AccueilControleur', 'cookies'],
'mes-documents'                  => ['DocumentControleur', 'index'],
'mes-documents/uploader'         => ['DocumentControleur', 'uploader'],
'mes-documents/telecharger/{id}' => ['DocumentControleur', 'telecharger'],
'mes-documents/supprimer/{id}'   => ['DocumentControleur', 'supprimer'],
'admin-etablissement/services'                => ['AdminEtablissementControleur', 'gererServices'],
'admin-etablissement/associer-service/{id}'   => ['AdminEtablissementControleur', 'associerService'],
'admin-etablissement/dissocier-service/{id}'  => ['AdminEtablissementControleur', 'dissocierService'],
];

$nomControleur = 'AccueilControleur';
$methode       = 'index';
$parametres    = [];
$found         = false;

if (isset($routes[$url])) {
    $nomControleur = $routes[$url][0];
    $methode       = $routes[$url][1];
    $found         = true;
} else {
    foreach ($routes as $pattern => $action) {
        $regex = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $pattern);
        if (preg_match('#^' . $regex . '$#', $url, $matches)) {
            array_shift($matches);
            $nomControleur = $action[0];
            $methode       = $action[1];
            $parametres    = $matches;
            $found         = true;
            break;
        }
    }
}

if (!$found) {
    $segments = explode('/', $url);
    $nomControleur = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controleur' : 'AccueilControleur';
    $methode       = isset($segments[1]) ? $segments[1] : 'index';
    $parametres    = array_slice($segments, 2);
}

if (class_exists($nomControleur)) {
    $instance = new $nomControleur();
    if (method_exists($instance, $methode)) {
        call_user_func_array([$instance, $methode], $parametres);
    } else {
        (new AccueilControleur())->erreur404();
    }
} else {
    (new AccueilControleur())->erreur404();
}
