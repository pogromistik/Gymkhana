<?php
/**
 * @var \yii\web\View               $this
 * @var \common\models\Championship $championship
 * @var array                       $results
 * @var \common\models\Stage[]      $stages
 * @var \common\models\Athlete      $athlete
 * @var integer                     $showAll
 */
$this->title = 'Результаты: ' . $championship->title;
?>

<h3><?= $championship->title ?>: <?= ($championship->status == \common\models\Championship::STATUS_PAST)
    ? 'итоги' : 'предварительные итоги' ?></h3>

<div class="about">
    Количество этапов, необходимое для участия в чемпионате: <?= $championship->amountForAthlete ?>
    <br>
    Необходимое количество этапов в других регионах:
	<?= $championship->requiredOtherRegions ?>
    <br>
    Количество этапов, по которым ведётся подсчёт результатов:
	<?= $championship->estimatedAmount ?>
    <br>
    Таблица, по которой прозводился расчёт баллов за каждый этап:
	<?php /** @var \common\models\Point[] $points */
	$points = \common\models\Point::find()->orderBy(['id' => SORT_ASC])->all() ?>
    <table class="table table-responsive table-bordered text-center">
        <tr>
            <td class="text-left"><b>место</b></td>
			<?php foreach ($points as $point) { ?>
                <td><?= $point->id ?></td>
			<?php } ?>
        </tr>
        <tr>
            <td class="text-left"><b>балл</b></td>
			<?php foreach ($points as $point) { ?>
                <td><?= $point->point ?></td>
			<?php } ?>
        </tr>
    </table>
</div>

<div class="pb-10">
	<?= \yii\bootstrap\Html::a('Скачать результаты в xls', \yii\helpers\Url::to([
		'/export/export',
		'modelId' => $championship->id,
		'type'    => \champ\controllers\ExportController::TYPE_CHAMPIONSHIP,
		'showAll'  => $showAll
	]), ['class' => 'btn btn-light']) ?>
</div>
<?php if ($showAll) { ?>
    В таблице приведены все спортсмены, выступившие хотя бы на одном из этапов, независимо от того, есть ли у них необходимое количество этапов.
    <br>
	<?= \yii\bootstrap\Html::a('Показать участников с необходимым количеством этапов',
        ['championship-result', 'championshipId' => $championship->id]) ?>
<?php } else { ?>
    В таблице приведены только спортсмены, принявшие участие в необходимом количестве этапов.
    <br>
	<?= \yii\bootstrap\Html::a('Показать всех участников',
		['championship-result', 'championshipId' => $championship->id, 'showAll' => true]) ?>
<?php } ?>
<table class="table table-responsive">
    <thead>
    <tr>
        <th>Место</th>
        <th>Класс</th>
        <th>Спортсмен</th>
		<?php foreach ($stages as $stage) { ?>
            <th><?= \yii\helpers\Html::a($stage->title, ['/competitions/stage', 'id' => $stage->id], ['target' => '_blank']) ?></th>
		<?php } ?>
        <th>Итого</th>
    </tr>
    </thead>
    <tbody>
	<?php
	$place = 0;
	$prevPoints = 0;
	$prevCount = 1;
	foreach ($results as $result) {
		?>
		<?php $athlete = $result['athlete'] ?>
		<?php
		if ($result['points'] > 0 && $result['points'] == $prevPoints) {
			$prevCount += 1;
		} else {
			$place += $prevCount;
			$prevCount = 1;
		}
		$prevPoints = $result['points'];
		?>
        <tr>
            <td><?= $place ?></td>
            <td><?= $athlete->athleteClassId ? $athlete->athleteClass->title : null ?></td>
            <td>
				<?= $athlete->getFullName() ?>
                <br>
				<?= $athlete->city->title ?>
            </td>
			<?php foreach ($stages as $stage) { ?>
				<?php if (isset($result['stages'][$stage->id])) { ?>
                    <td><?= $result['stages'][$stage->id] ?></td>
				<?php } else { ?>
                    <td>0</td>
				<?php } ?>
			<?php } ?>
            <td><?= $result['points'] ?></td>
        </tr>
	<?php } ?>
    </tbody>
</table>
