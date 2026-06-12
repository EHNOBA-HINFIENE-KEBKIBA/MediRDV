<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2>Foire aux questions</h2>
<?php if (empty($faqs)): ?>
    <p>Aucune question pour le moment.</p>
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