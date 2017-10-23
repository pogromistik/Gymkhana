<?php
/**
 * @var \yii\web\View               $this
 * @var \common\models\Championship $championship
 * @var array                       $results
 * @var \common\models\Stage[]      $stages
 * @var \common\models\Athlete      $athlete
 * @var integer                     $showAll
 * @var \common\models\Stage[]      $outOfChampStages
 */
$this->title = 'Результаты: ' . $championship->title;
?>
<?php if ($championship->showResults) { ?>
    <h3><?= $championship->title ?>: <?= ($championship->status == \common\models\Championship::STATUS_PAST)
			? 'итоги' : 'предварительные итоги' ?></h3>
<?php } else { ?>
    <h3><?= $championship->title ?></h3>
<?php } ?>

<div class="about">
	<?php if ($championship->onlyRegions && $championship->isClosed) { ?>
        <div>
            Регионы, допускающиеся к участию: <?= $championship->getRegionsFor(true) ?>
        </div>
	<?php } ?>
    Количество этапов, необходимое для участия в чемпионате: <?= $championship->amountForAthlete ?>
    <br>
    Необходимое количество этапов в других регионах:
	<?= $championship->requiredOtherRegions ?>
    <br>
    Количество этапов, по которым ведётся подсчёт результатов:
	<?= $championship->estimatedAmount ?>
    <br>
	<?php if (!$championship->useMoscowPoints) { ?>
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
	<?php } else { ?>
        Подсчёт баллов ведётся по <a href="/competitions/moscow-scheme" target="_blank">Московской схеме</a> .
	<?php } ?>
	<?php if ($outOfChampStages) { ?>
        <div class="pt-10 pb-10">
            <b>Следующие этапы проводились вне зачёта:</b><br>
            <ul>
				<?php foreach ($outOfChampStages as $outOfChampStage) { ?>
                    <li><?= $outOfChampStage->title ?></li>
				<?php } ?>
            </ul>
            Баллы за эти этапы не учитываются при подсчёте итоговой суммы. В таблице такие этапы выделены серым цветом.
        </div>
	<?php } ?>
</div>

<div class="pb-10">
	<?= \yii\bootstrap\Html::a('Скачать результаты в xls', \yii\helpers\Url::to([
		'/export/export',
		'modelId' => $championship->id,
		'type'    => \champ\controllers\ExportController::TYPE_CHAMPIONSHIP,
		'showAll' => $showAll
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
		<?php foreach ($stages as $stage) {
			$class = '';
			if ($stage->outOfCompetitions) {
				$class = 'gray-column';
			}
			?>
            <th class="<?= $class ?>"><?= \yii\helpers\Html::a($stage->title, ['/competitions/stage', 'id' => $stage->id]) ?></th>
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
			<?php foreach ($stages as $stage) {
				$class = '';
				if ($stage->outOfCompetitions) {
					$class = 'gray-column';
				}
				?>
				<?php if (isset($result['stages'][$stage->id])) { ?>
                    <td class="<?= $class ?>"><?= $result['stages'][$stage->id] ?></td>
				<?php } else { ?>
                    <td class="<?= $class ?>">0</td>
				<?php } ?>
			<?php } ?>
            <td><?= $result['points'] ?></td>
        </tr>
	<?php } ?>
    </tbody>
</table>
