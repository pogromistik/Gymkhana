<?php
use champ\controllers\CompetitionsController;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 */
?>
<h2><?= \Yii::t('app', 'Результаты соревнований') ?></h2>
<div class="change-result-type">
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_FIGURES]) ?>">
            <?= \Yii::t('app', 'Базовые фигуры') ?>
        </a></div>
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_RUSSIA]) ?>">
            <?= \Yii::t('app', 'Чемпионаты России и мира') ?>
        </a></div>
    <div><a href="<?= Url::to(['/competitions/results', 'by' => CompetitionsController::RESULTS_REGIONAL]) ?>">
            <?= \Yii::t('app', 'Региональные соревнования') ?>
        </a></div>
</div>