<?php
/**
 * @var \yii\web\View               $this
 * @var \common\models\Championship $championship
 * @var array                       $results
 * @var \common\models\Stage[]      $stages
 * @var \common\models\Athlete      $athlete
 * @var integer                     $showAll
 */
$this->title = \Yii::t('app', 'Результаты: {title}', ['title' => $championship->title]);
?>
<?php if ($championship->showResults) { ?>
<h3><?= $championship->title ?>: <?= ($championship->status == \common\models\Championship::STATUS_PAST)
    ? \Yii::t('app', 'итоги') : \Yii::t('app', 'предварительные итоги') ?></h3>
<?php } else { ?>
    <h3><?= $championship->title ?></h3>
<?php } ?>

<div class="about">
	<?php if ($championship->onlyRegions && $championship->isClosed) { ?>
        <div>
	        <?= \Yii::t('app', 'Регионы, допускающиеся к участию: {regionTitles}',
		        ['regionTitles' => $championship->getRegionsFor(true)]) ?>
        </div>
	<?php } ?>
	<?= \Yii::t('app', 'Количество этапов, необходимое для участия в чемпионате: {count}', [
		'count' => $championship->amountForAthlete
	]) ?>
    <br>
	<?= \Yii::t('app', 'Необходимое количество этапов в других регионах: {count}', [
		'count' => $championship->requiredOtherRegions
	]) ?>
    <br>
	<?= \Yii::t('app', 'Количество этапов, по которым ведётся подсчёт результатов: {count}', [
		'count' => $championship->estimatedAmount
	]) ?>
    <?php if(!$championship->useMoscowPoints) { ?>
    <?= \Yii::t('app', 'Таблица, по которой производился расчёт баллов за каждый этап:') ?>
	<?php /** @var \common\models\Point[] $points */
	$points = \common\models\Point::find()->orderBy(['id' => SORT_ASC])->all() ?>
    <table class="table table-responsive table-bordered text-center">
        <tr>
            <td class="text-left"><b><?= \Yii::t('app', 'место') ?></b></td>
			<?php foreach ($points as $point) { ?>
                <td><?= $point->id ?></td>
			<?php } ?>
        </tr>
        <tr>
            <td class="text-left"><b><?= \Yii::t('app', 'балл') ?></b></td>
			<?php foreach ($points as $point) { ?>
                <td><?= $point->point ?></td>
			<?php } ?>
        </tr>
    </table>
    <?php } else { ?>
        <a href="/competitions/moscow-scheme" target="_blank"><?= \Yii::t('app', 'Подсчёт баллов ведётся по Московской схеме') ?></a> .
    <?php } ?>
</div>

<div class="pb-10">
	<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Скачать результаты в xls'), \yii\helpers\Url::to([
		'/export/export',
		'modelId' => $championship->id,
		'type'    => \champ\controllers\ExportController::TYPE_CHAMPIONSHIP,
		'showAll'  => $showAll
	]), ['class' => 'btn btn-light']) ?>
</div>
<?php if ($showAll) { ?>
    <?= \Yii::t('app', 'В таблице приведены все спортсмены, выступившие хотя бы на одном из этапов, независимо от того, есть ли у них необходимое количество этапов.') ?>
    <br>
	<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Показать участников с необходимым количеством этапов'),
        ['championship-result', 'championshipId' => $championship->id]) ?>
<?php } else { ?>
    <?= \Yii::t('app', 'В таблице приведены только спортсмены, принявшие участие в необходимом количестве этапов.') ?>
    <br>
	<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Показать всех участников'),
		['championship-result', 'championshipId' => $championship->id, 'showAll' => true]) ?>
<?php } ?>
<table class="table table-responsive">
    <thead>
    <tr>
        <th><?= \Yii::t('app', 'Место') ?></th>
        <th><?= \Yii::t('app', 'Класс') ?></th>
        <th><?= \Yii::t('app', 'Спортсмен') ?></th>
		<?php foreach ($stages as $stage) { ?>
            <th><?= \yii\helpers\Html::a($stage->title, ['/competitions/stage', 'id' => $stage->id]) ?></th>
		<?php } ?>
        <th><?= \Yii::t('app', 'Итого') ?></th>
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
				<?= \common\helpers\TranslitHelper::translitCity($athlete->city->title) ?>
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
