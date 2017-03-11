<?php
use common\models\Figure;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;

/**
 * @var \yii\web\View               $this
 * @var Figure                      $figure
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Year | null  $year
 * @var bool                        $showAll
 */
$time = time();
?>

<div class="title-with-bg">
	<?= $figure->title ?>
	<?php if ($year) { ?>
        , <?= $year->year ?>
	<?php } ?>
</div>

<div class="pt-20">
    <div class="description pb-20">
		<?php if ($figure->description) { ?>
            <p><?= $figure->description ?></p>
		<?php } ?>
        <div class="records">
            <b>Мировой рекорд:</b>
			<?php if ($figure->bestAthlete) { ?>
				<?= $figure->bestAthlete ?>
			<?php } ?>
			<?= $figure->bestTimeForHuman ?>
			<?php if ($figure->bestAthleteInRussia || $figure->bestTimeInRussia) { ?>
                <br>
                <b>Рекорд в России:</b>
				<?php if ($figure->bestAthleteInRussia) { ?>
					<?= $figure->bestAthleteInRussia ?>
				<?php } ?>
				<?php if ($figure->bestTimeInRussia) { ?>
					<?= $figure->bestTimeInRussiaForHuman ?>
				<?php } ?>
			<?php } ?>
        </div>
		<?php if ($figure->picture) { ?>
            <div class="track-photo">
                <div class="toggle">
                    <div class="title">Посмотреть схему</div>
                    <div class="toggle-content">
		                <?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $figure->picture) ?>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>

    <div class="filters">
		<?= \yii\bootstrap\Html::beginForm('', 'post', ['id' => 'figureFilterForm']) ?>
		<?= \yii\bootstrap\Html::hiddenInput('figureId', $figure->id) ?>
		<?= \yii\bootstrap\Html::hiddenInput('yearId', $year ? $year->id : null) ?>
		<?= \yii\bootstrap\Html::hiddenInput('showAll', $showAll, ['id' => 'showAll']) ?>
        <div class="row">
            <div class="col-md-6 col-sm-12">
				<?= Select2::widget([
					'name'          => 'regionIds',
					'data'          => \common\models\Region::getAll(true),
					'maintainOrder' => true,
					'options'       => ['placeholder' => 'Выберите регион...', 'multiple' => true],
					'pluginOptions' => [
						'tags'               => true,
						'maximumInputLength' => 10
					],
					'pluginEvents'  => [
						'change' => 'function(e){
				figureFilters();
			}',
					]
				]);
				?>
            </div>
            <div class="col-md-6 col-sm-12">
				<?= Select2::widget([
					'name'          => 'classIds',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()
						->where(['status' => \common\models\AthletesClass::STATUS_ACTIVE])
						->orderBy(['percent' => SORT_ASC])->all(), 'id', 'title'),
					'maintainOrder' => true,
					'options'       => ['placeholder' => 'Выберите класс...', 'multiple' => true],
					'pluginOptions' => [
						'tags'               => true,
						'maximumInputLength' => 10
					],
					'pluginEvents'  => [
						'change' => 'function(e){
				figureFilters();
			}',
					]
				]);
				?>
            </div>
        </div>
		<?php \yii\bootstrap\Html::endForm() ?>
    </div>

    <div class="alert alert-danger" style="display: none"></div>

    <div class="results pt-20">
        <div class="small text-right">
			<?php $count = count($results); ?>
			<?php if ($count > 30) { ?>
                Показаны 30 лучших результатов. <a href="#" class="showAll">Показать все</a>
			<?php } else { ?>
                Количество результатов: <?= $count ?>
			<?php } ?>
        </div>
		
		<?= $this->render('_figure-result', ['results' => $results]) ?>
    </div>
</div>

<a href="<?= \yii\helpers\Url::to(['/competitions/results', 'active' => 'figures']) ?>">Вернуться к фигурам</a>