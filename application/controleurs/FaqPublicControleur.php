<?php
class FaqPublicControleur extends Controleur {
    public function index() {
        $faqs = (new Faq())->toutes();
        $this->afficherVue('faq_public', ['titre'=>'FAQ', 'faqs'=>$faqs]);
    }
}