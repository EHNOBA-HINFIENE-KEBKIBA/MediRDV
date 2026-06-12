<?php
class BlogArticle extends Modele {
    protected $table = 'blog_articles';

    public function derniers($limite = 5) {
        $stmt = $this->pdo->prepare("SELECT a.*, u.nom as auteur_nom, u.prenom as auteur_prenom FROM {$this->table} a LEFT JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur ORDER BY date_publication DESC LIMIT :limite");
        $stmt->bindValue('limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tous() {
        return $this->pdo->query("SELECT * FROM {$this->table} ORDER BY date_publication DESC")->fetchAll();
    }

    public function trouverParId($id) {
        $stmt = $this->pdo->prepare("SELECT a.*, u.nom as auteur_nom, u.prenom as auteur_prenom FROM {$this->table} a LEFT JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur WHERE a.id_article = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function ajouter($titre, $contenu, $id_auteur) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (titre, contenu, id_auteur) VALUES (:titre, :contenu, :auteur)");
        return $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'auteur' => $id_auteur]);
    }

    public function modifier($id, $titre, $contenu) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET titre=:titre, contenu=:contenu WHERE id_article=:id");
        return $stmt->execute(['titre'=>$titre,'contenu'=>$contenu,'id'=>$id]);
    }

    public function supprimer($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_article=:id");
        return $stmt->execute(['id'=>$id]);
    }
}