<?php
use common\models\SpecialStage;
use common\models\Stage;
use kartik\widgets\Select2;
use yii\helpers\Html;

/**
 * @var \common\models\SpecialStage             $stage
 * @var array                                   $needTime
 * @var \common\models\RequestForSpecialStage[] $activeParticipants
 * @var array                                   $regionIds
 * @var \common\models\Region[]                 $regions
 */

$timezone = '(' . \Yii::t('app', 'Москва') . ', UTC +3)';
$championship = $stage->championship;
?>

<div class="row stage">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <div class="title-with-bg">
			<?= $championship->getTitle() ?>
        </div>

        <div>
			<?= Html::a(\Yii::t('app', 'Подробнее о чемпионате'), ['/competitions/special-champ', 'id' => $championship->id]) ?>
        </div>

        <div class="pl-10">
            <h4><?= $stage->getTitle() ?>
                <span class="label <?= ($stage->status == SpecialStage::STATUS_CANCEL) ?
					'label-danger' : 'label-success' ?>"><?= SpecialStage::$statusesTitle[$stage->status] ?></span></h4>
			
			<?php if ($stage->getDescr()) { ?>
                <p><?= $stage->getDescr() ?></p>
			<?php } ?>
			
			<?php if ($stage->dateStart) { ?>
				<?= \Yii::t('app', 'Начало приёма результатов') ?>: <?= $stage->dateStartHuman ?> <?= $timezone ?><br>
			<?php } ?>
			<?php if ($stage->dateEnd) { ?>
				<?= \Yii::t('app', 'Завершение приёма результатов') ?>: <?= $stage->dateEndHuman ?> <?= $timezone ?><br>
			<?php } ?>
			<?php if ($stage->dateResult) { ?>
				<?= \Yii::t('app', 'Подведение итогов') ?>: <?= $stage->dateResultHuman ?><br>
			<?php } ?>
			
			<?php if ($stage->classId) { ?>
                <div>
					<?php $stageClassTitle = $stage->class->title; ?>
					<?= \Yii::t('app', 'Класс соревнования: {class}', ['class' => $stageClassTitle]) ?>
					
					<?php if ($stageClassTitle == \common\models\Stage::CLASS_UNPERCENT) { ?>
                        <div><b>
								<?= \Yii::t('app', 'Т.к. класс соревнования {classTitle}, рейтинг спортсменов и эталонное время трассы не рассчитывается', [
									'classTitle' => $stageClassTitle
								]) ?></b></div>
					<?php } ?>
                </div>
			<?php } ?>
			
			<?php if ($stage->photoPath) { ?>
                <div class="track-photo pt-20 pb-20">
                    <div class="toggle">
                        <div class="title btn btn-green"><?= \Yii::t('app', 'Посмотреть схему трассы') ?></div>
                        <div class="toggle-content">
							<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->photoPath) ?>
                        </div>
                    </div>
                </div>
			<?php } ?>
			
			<?php if ($stage->referenceTime && $stage->class && $stage->class->title != Stage::CLASS_UNPERCENT) { ?>
                <div>
					<?= \Yii::t('app', 'Эталонное время трассы') ?>: <?= $stage->referenceTimeHuman ?>
                    <br>
					<?= \Yii::t('app', 'Время, необходимое для повышения класса') ?>:
                    <table class="table">
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
										<?= \Yii::t('app', 'Класс') ?>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
										<?= \Yii::t('app', 'Процент') ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
										<?= \Yii::t('app', 'Минимальное время') ?>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
										<?= \Yii::t('app', 'Максимальное время') ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
						<?php foreach ($needTime as $id => $data) {
							$cssClass = null;
							if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')])) {
								$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')];
							}
							?>
                            <tr class="result-<?= $cssClass ?>">
                                <td>
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
											<?= $data['classModel']->title ?>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
											<?= $data['percent'] ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
											<?= $data['startTime'] ?>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
											<?= $data['endTime'] ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
						<?php } ?>
                    </table>
                </div>
			<?php } ?>
			
			
			<?php if ($stage->status == SpecialStage::STATUS_CANCEL) { ?>
                <div class="warning">
                    <div class="text-center">
						<?= \Yii::t('app', 'ЭТАП ОТМЕНЁН') ?><br>
						<?= \Yii::t('app', ' Для уточнения подробностей обратитесь к организаторам соревнования.') ?>
                    </div>
                </div>
			<?php } else { ?>
				<?php if ($stage->isOpen()) { ?>
					<?php if (\Yii::$app->user->isGuest) { ?>
                        <a href="#"
                           class="btn btn-dark sendResultForStage"><?= \Yii::t('app', 'Отправить результат') ?></a>
						<?php $view = '_guest-registration'; ?>
					<?php } else { ?>
                        <a href="#"
                           class="btn btn-dark sendResultForStage"><?= \Yii::t('app', 'Отправить результат') ?></a>
						<?php $view = '_auth-registration'; ?>
					<?php } ?>
                    <div class="special-stage-form">
						<?= $this->render($view, ['stage' => $stage]) ?>
                    </div>
				<?php } ?>

                <div class="filters pt-20 pb-20">
					<?= \yii\bootstrap\Html::beginForm('/competitions/special-stage', 'get') ?>
					<?= \yii\bootstrap\Html::hiddenInput('id', $stage->id) ?>
                    <div class="row">
                        <div class="col-md-10 col-xs-8 input-with-sm-pt">
							<?= Select2::widget([
								'name'          => 'regionIds',
								'value'         => $regionIds,
								'data'          => \yii\helpers\ArrayHelper::map($regions, 'id', 'title'),
								'options'       => [
									'placeholder' => \Yii::t('app', 'Выберите регионы') . '...',
									'multiple'    => true
								],
								'pluginOptions' => [
									'allowClear' => true
								]
							]) ?>
                        </div>
                        <div class="col-md-2 col-xs-4">
							<?= Html::submitButton(\Yii::t('app', 'ок'), ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
					<?= \yii\bootstrap\Html::endForm() ?>
                </div>

                <div class="text-right">
					<?= \Yii::t('app', 'Количество участников') ?>: <?= count($activeParticipants) ?>
                    <br>
                    <small><?= \Yii::t('app', 'Для просмотра прогресса нажмите на время') ?></small>
                </div>
				<?= $this->render('_pk-results', ['participants' => $activeParticipants]) ?>
				<?= $this->render('_mobile-results', ['participants' => $activeParticipants]) ?>
			<?php } ?>
        </div>
    </div>

    <div class="col-bg-4 col-lg-3 col-md-2 col-sm-12 list-nav">
		<?php
		$stages = $stage->championship->stages;
		if ($stages) {
			?>
            <ul>
				<?php foreach ($stages as $item) { ?>
                    <li>
						<?= Html::a($item->getTitle(), ['/competitions/special-stage', 'id' => $item->id]) ?>
                    </li>
				<?php } ?>
                <li>
					<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/special-champ-result', 'championshipId' => $stage->championshipId]) ?>
                </li>
            </ul>
			<?php
		}
		?>
    </div>
</div>
