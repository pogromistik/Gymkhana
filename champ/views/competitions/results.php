<?php
use champ\controllers\CompetitionsController;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 */
?>
<h2>Результаты соревнований</h2>
<div class="change-result-type">
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_FIGURES]) ?>">Базовые фигуры</a></div>
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_RUSSIA]) ?>">Чемпионаты России и мира</a></div>
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_REGIONAL]) ?>">Региональные соревнования</a></div>
</div>