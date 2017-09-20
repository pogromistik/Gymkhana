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
        <b><?= $championship->year->year ?> год</b>
        <span class="label label-info"><?= \common\models\Championship::$statusesTitle[$championship->status] ?></span>
		<?php if ($championship->regionId) { ?>
            <div class="pb-10">
                Регион проведения: <?= $championship->region->title ?>
            </div>
		<?php } ?>
		<?php if ($championship->onlyRegions && $championship->isClosed) { ?>
            <div class="pb-10">
                Регионы, допускающиеся к участию: <?= $championship->getRegionsFor(true) ?>
            </div>
		<?php } ?>
		<?php if ($championship->description) { ?>
            <div class="pt-20">
				<?= $championship->description ?>
            </div>
		<?php } ?>
        <div>
            Количество этапов, необходимое для участия в чемпионате: <?= $championship->amountForAthlete ?>
            <br>
            Необходимое количество этапов в других регионах:
			<?= $championship->requiredOtherRegions ?>
            <br>
            Количество этапов, по которым ведётся подсчёт результатов:
			<?= $championship->estimatedAmount ?>
			<?php if ($championship->requiredOtherRegions) { ?>
                <br>
                Для полноценного участия в чемпионате необходимо хоть раз выступить на этапе в другом городе.
			<?php } ?>
            <br>
            Диапазон стартовых номеров участников: <?= $championship->minNumber ?>-<?= $championship->maxNumber ?>.
        </div>
		<?php if ($championship->activeInternalClasses) { ?>
            <div class="pt-10 pb-10">
				<?php
				$internalClasses = [];
				foreach ($championship->activeInternalClasses as $class) {
					$internalClasses[] = $class->title;
				}
				?>
                <b>Классы награждения:</b> <?= implode(', ', $internalClasses) ?>
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
						<?= Html::a('Итоги чемпионата', ['/competitions/championship-result', 'championshipId' => $championship->id]) ?>
                    </li>
				<?php } ?>
            </ul>
			<?php
		}
		?>
    </div>
</div>