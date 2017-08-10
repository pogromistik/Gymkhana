<?php
/**
 * @var \yii\web\View               $this
 * @var \common\models\FigureTime[] $items
 */
$this->title = 'Повторы спортсменов по фамилии';
?>

<div class="row pb-10">
    <div class="col-sm-2"><b>Спортсмен</b></div>
    <div class="col-sm-2"><b>Мотоцикл</b></div>
    <div class="col-sm-2"><b>Фигура</b></div>
    <div class="col-sm-2"><b>Дата</b></div>
    <div class="col-sm-2"><b>Время</b></div>
    <div class="col-sm-2"><b>Штраф</b></div>
</div>
<?php
$lastName = null;
$figureId = null;
$athleteId = null;
foreach ($items as $item) { ?>
	<?php if ($figureId && $athleteId && ($figureId != $item->figureId || $athleteId != $item->athleteId)) { ?>
        <hr>
	<?php } ?>
    <div class="row">
        <div class="col-sm-2">
			<?= \yii\helpers\Html::a($item->athlete->getFullName(), ['/competitions/athlete/update', 'id' => $item->athleteId]) ?>
        </div>
        <div class="col-sm-2">
			<?= $item->motorcycle->getFullTitle() ?>
        </div>
        <div class="col-sm-2">
			<?= \yii\helpers\Html::a($item->figure->title, ['/competitions/figures/update', 'id' => $item->figureId]) ?>
        </div>
        <div class="col-sm-2">
			<?= $item->dateForHuman ?>
        </div>
        <div class="col-sm-2">
			<?= $item->timeForHuman ?>
        </div>
        <div class="col-sm-2">
			<?= $item->fine ?>
        </div>
    </div>
	<?php $athleteId = $item->athleteId;
	$figureId = $item->figureId; ?>
<?php } ?>

