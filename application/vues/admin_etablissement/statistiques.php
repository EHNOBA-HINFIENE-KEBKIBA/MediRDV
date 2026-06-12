<?php if (!defined('BASE_URL')) define('BASE_URL', '/MediRDV'); ?>
<h2><?= $titre ?></h2>
<div class="card p-4">
    <canvas id="chartRdv"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('chartRdv').getContext('2d');
var data = [0,0,0,0,0,0,0,0,0,0,0,0];
<?php foreach ($stats as $s): ?>
data[<?= $s['mois']-1 ?>] = <?= $s['total'] ?>;
<?php endforeach; ?>
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'],
        datasets: [{
            label: 'Rendez-vous par mois',
            data: data,
            backgroundColor: '#0d6efd'
        }]
    }
});
</script>