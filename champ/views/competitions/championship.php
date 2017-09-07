<?php
/**
 * @var \yii\web\View               $this
 * @var \common\models\Championship $championship
 */
use yii\bootstrap\Html;

$this->title = $championship->title;
?>

<h3><?= $championship->title ?></h3>

<div class="row">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <b><?= \Yii::t('app', '{year} год', ['year' => $championship->year->year]) ?></b>
        <span class="label label-info"><?= \Yii::t('app',
		        \common\models\Championship::$statusesTitle[$championship->status]) ?></span>
		<?php if ($championship->regionId) { ?>
            <div class="pb-10">
				<?= \Yii::t('app', 'Регион проведения: {regionTitle}', ['regionTitle' => $championship->region->title]) ?>
            </div>
		<?php } ?>
		<?php if ($championship->onlyRegions && $championship->isClosed) { ?>
            <div class="pb-10">
				<?= \Yii::t('app', 'Регионы, допускающиеся к участию: {regionTitles}',
					['regionTitles' => $championship->getRegionsFor(true)]) ?>
            </div>
		<?php } ?>
		<?php if ($championship->description) { ?>
            <div class="pt-20">
				<?= $championship->description ?>
            </div>
		<?php } ?>
        <div>
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
			<?php if ($championship->requiredOtherRegions) { ?>
                <br>
				<?= \Yii::t('app', 'Для полноценного участия в чемпионате необходимо хоть раз выступить на этапе в другом городе.') ?>
			<?php } ?>
            <br>
			<?= \Yii::t('app', 'Диапазон стартовых номеров участников: {minNumber}-{maxNumber}', [
				'minNumber' => $championship->minNumber,
				'maxNumber' => $championship->maxNumber
			]) ?>.
        </div>
		<?php if ($championship->activeInternalClasses) { ?>
            <div class="pt-10 pb-10">
				<?php
				$internalClasses = [];
				foreach ($championship->activeInternalClasses as $class) {
					$internalClasses[] = $class->title;
				}
				?>
                <b><?= \Yii::t('app', 'Классы награждения: {classes}', [
                        'classes' => implode(', ', $internalClasses)
                    ]) ?></b>
            </div>
		<?php } ?>
    </div>

    <div class="col-bg-4 col-lg-3 col-md-2 col-sm-12 list-nav">
		<?php
		$stages = $championship->stages;
		if ($stages) {
			?>
            <ul>
				<?php foreach ($stages as $item) { ?>
                    <li>
						<?= Html::a($item->title, ['/competitions/stage', 'id' => $item->id]) ?>
                    </li>
				<?php } ?>
				<?php if ($championship->showResults) { ?>
                    <li>
						<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/championship-result', 'championshipId' => $championship->id]) ?>
                    </li>
				<?php } ?>
            </ul>
			<?php
		}
		?>
    </div>
</div>