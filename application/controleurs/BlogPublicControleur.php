<?php
class BlogPublicControleur extends Controleur {
    public function index() {
        $articles = (new BlogArticle())->derniers(10);
        $this->afficherVue('blog_public', ['titre'=>'Blog Santé', 'articles'=>$articles]);
    }

    public function article($id) {
        $article = (new BlogArticle())->trouverParId($id);
        if (!$article) (new AccueilControleur())->erreur404();
        $this->afficherVue('blog_detail', ['titre'=>$article['titre'], 'article'=>$article]);
    }
}