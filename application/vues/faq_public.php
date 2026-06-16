<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<style>
    .accordion-button {
        font-weight: 600;
    }
    .accordion-item {
        border: none;
        margin-bottom: 0.5rem;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>

<div class="container py-5">
    <h1 class="fw-bold mb-2"><i class="bi bi-question-circle me-2 text-primary"></i>Foire aux questions</h1>
    <p class="text-muted mb-5">Les réponses aux questions les plus fréquentes.</p>

    <?php if (empty($faqs)): ?>
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown fs-1 text-muted"></i>
            <p class="mt-3 text-muted">Aucune question pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="accordion" id="faqAccordion">
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button <?= $index==0?'':'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $index ?>">
                        <?= htmlspecialchars($faq['question']) ?>
                    </button>
                </h2>
                <div id="faq<?= $index ?>" class="accordion-collapse collapse <?= $index==0?'show':'' ?>" data-bs-parent="#faqAccordion">
                    <div class="accordion-body"><?= nl2br(htmlspecialchars($faq['reponse'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>