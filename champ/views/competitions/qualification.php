<?php
/**
 * @var \common\models\Stage $stage
 * @var array | null         $qualification
 */
$time = time();
$city = $stage->city;
if ($city->timezone) {
	$timezone = '(' . $city->title . ', UTC ' . $city->utc . ')';
} else {
	$timezone = '(Москва, UTC +3)';
}

$championship = $stage->championship;
?>

<div class="row stage">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <div class="title-with-bg">
			<?= $championship->title ?>
        </div>

        <div class="pl-10">
            <h4><?= $stage->title ?>
                , <?= $stage->city->title ?> <?php if ($stage->dateOfThe) { ?>, <?= $stage->dateOfTheHuman ?><?php } ?>
            </h4>
            <?= \yii\helpers\Html::a('Вернуться к этапу', ['/competitions/stage', 'id' => $stage->id], ['class' => 'btn btn-dark']) ?>

			<?php if ($qualification) { ?>
                <h3>Результаты квалификационных заездов</h3>
                <p>На усмотрение организаторов, у участников может быть несколько попыток или не быть вовсе.</p>
				<?= $this->render('_figure-result-for-stage', ['results' => $qualification['results']]) ?>
				<?= \yii\helpers\Html::a('Вернуться к этапу', ['/competitions/stage', 'id' => $stage->id]) ?>
			<?php } else { ?>
                <h3>Результаты квалификационных заездов отсутствуют</h3>
            <?php } ?>
        </div>
    </div>
</div>
