<?php
class BlogFaqControleur extends Controleur {

    private function verifierSuperAdmin() {
        if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role_id'] != 1) {
            $this->rediriger('/connexion');
        }
    }

    // ==================== BLOG ====================
    public function blogAdmin() {
        $this->verifierSuperAdmin();
        $articles = (new BlogArticle())->tous();
        $this->afficherVuePrivee('admin/blog_articles', ['titre'=>'Articles du blog', 'articles'=>$articles]);
    }

    public function ajouterArticle() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            (new BlogArticle())->ajouter($titre, $contenu, $_SESSION['utilisateur_id']);
        }
        $this->rediriger('/admin/blog');
    }

    public function modifierArticle($id) {
        $this->verifierSuperAdmin();
        $article = (new BlogArticle())->trouverParId($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new BlogArticle())->modifier($id, $_POST['titre'], $_POST['contenu']);
            $this->rediriger('/admin/blog');
        }
        $this->afficherVuePrivee('admin/modifier_article', ['titre'=>'Modifier article', 'article'=>$article]);
    }

    public function supprimerArticle($id) {
        $this->verifierSuperAdmin();
        (new BlogArticle())->supprimer($id);
        $this->rediriger('/admin/blog');
    }

    // ==================== FAQ ====================
    public function faqAdmin() {
        $this->verifierSuperAdmin();
        $faqs = (new Faq())->toutes();
        $this->afficherVuePrivee('admin/faq', ['titre'=>'Gestion FAQ', 'faqs'=>$faqs]);
    }

    public function ajouterFaq() {
        $this->verifierSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Faq())->ajouter($_POST['question'], $_POST['reponse']);
        }
        $this->rediriger('/admin/faq');
    }

    public function supprimerFaq($id) {
        $this->verifierSuperAdmin();
        (new Faq())->supprimer($id);
        $this->rediriger('/admin/faq');
    }
}