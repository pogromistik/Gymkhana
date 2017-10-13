<?php
use common\models\SpecialStage;

/**
 * @var \common\models\SpecialStage             $stage
 * @var array                                   $needTime
 * @var \common\models\RequestForSpecialStage[] $activeParticipants
 */

$timezone = '(Москва, UTC +3)';
$championship = $stage->championship;
?>

<div class="row stage">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <div class="title-with-bg">
			<?= $championship->title ?>
        </div>

        <div class="pl-10">
            <h4><?= $stage->title ?>
                <span class="label <?= ($stage->status == SpecialStage::STATUS_CANCEL) ?
					'label-danger' : 'label-success' ?>"><?= SpecialStage::$statusesTitle[$stage->status] ?></span></h4>
			
			<?php if ($stage->description) { ?>
                <p><?= $stage->description ?></p>
			<?php } ?>
			
			<?php if ($stage->dateStart) { ?>
                Начало приёма результатов: <?= $stage->dateStartHuman ?> <?= $timezone ?><br>
			<?php } ?>
			<?php if ($stage->dateEnd) { ?>
                Завершение приёма результатов: <?= $stage->dateEndHuman ?> <?= $timezone ?><br>
			<?php } ?>
			<?php if ($stage->dateResult) { ?>
                Подведение итогов: <?= $stage->dateResultHuman ?><br>
			<?php } ?>
			
			<?php if ($stage->classId) { ?>
                <div>
					<?php $stageClassTitle = $stage->class->title; ?>
                    Класс соревнования: <?= $stageClassTitle ?>
					<?php if ($stageClassTitle == \common\models\Stage::CLASS_UNPERCENT) { ?>
                        <div><b>Т.к. класс соревнования <?= $stageClassTitle ?>, рейтинг спортсменов
                                и эталонное время трассы не
                                рассчитывается</b></div>
					<?php } ?>
                </div>
			<?php } ?>
			
			<?php if ($stage->photoPath) { ?>
                <div class="track-photo pt-20 pb-20">
                    <div class="toggle">
                        <div class="title btn btn-green">Посмотреть схему трассы</div>
                        <div class="toggle-content">
							<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->photoPath) ?>
                        </div>
                    </div>
                </div>
			<?php } ?>
			
			<?php if ($stage->referenceTime && $stage->class && $stage->classModel->title != Stage::CLASS_UNPERCENT) { ?>
                <div>
                    Эталонное время трассы: <?= $stage->referenceTimeHuman ?>
                    <br>
                    Время, необходимое для повышения класса:
                    <table class="table">
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        Класс
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        Процент
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        Минимальное время
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        Максимальное время
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
                        ЭТАП ОТМЕНЁН<br>
                        Для уточнения подробностей обратитесь к организаторам соревнования.
                    </div>
                </div>
			<?php } else { ?>
				<?php if ($stage->isOpen()) { ?>
					<?php if (\Yii::$app->user->isGuest) { ?>
                        <a href="#" class="btn btn-dark sendResultForStage">Отправить результат</a>
						<?php $view = '_guest-registration'; ?>
					<?php } else { ?>
                        <a href="#" class="btn btn-dark sendResultForStage">Отправить результат</a>
						<?php $view = '_auth-registration'; ?>
					<?php } ?>
                    <div class="special-stage-form">
						<?= $this->render($view, ['stage' => $stage]) ?>
                    </div>
				<?php } ?>
                <div class="text-right">
                    Количество участников: <?= count($activeParticipants) ?>
                    <br>
                    <small>Для просмотра прогресса нажмите на время</small>
                </div>
				<?= $this->render('_pk-results', ['participants' => $activeParticipants]) ?>
				<?= $this->render('_mobile-results', ['participants' => $activeParticipants]) ?>
			<?php } ?>
        </div>
    </div>
</div>
