<?php
use common\models\Stage;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                   $this
 * @var \common\models\Stage            $stage
 * @var \common\models\Participant[]    $participantsByJapan
 * @var \common\models\Participant[]    $participantsByInternalClasses
 * @var integer                         $sortBy
 * @var \common\models\TmpParticipant[] $tmpParticipants
 */
$time = time();
$city = $stage->city;
if ($city->timezone) {
	$timezone = '(' . $city->title . ', UTC ' . $city->utc . ')';
} else {
	$timezone = '(Москва, UTC +3)';
}

$championship = $stage->championship;
$countParticipants = count($participantsByInternalClasses) + count($tmpParticipants) + count($stage->outParticipants);
?>

    <div class="row">
        <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
            <div class="title-with-bg">
				<?= $championship->title ?>
            </div>

            <div class="pl-10">
                <h4><?= $stage->title ?>
                    , <?= $stage->city->title ?> <?php if ($stage->dateOfThe) { ?>, <?= $stage->dateOfTheHuman ?><?php } ?>
                    <span class="label label-success"><?= Stage::$statusesTitle[$stage->status] ?></span></h4>
				<?php if ($stage->location) { ?>
                    <p>Место проведения этапа: <?= $stage->location ?></p>
				<?php } ?>
				
				<?php if ($stage->description) { ?>
                    <p><?= $stage->description ?></p>
				<?php } ?>
				<?php if ($stage->status == Stage::STATUS_UPCOMING || $stage->status == Stage::STATUS_START_REGISTRATION) { ?>
					<?php if ($stage->startRegistration) { ?>
                        <p>
                            Начало регистрации: <?= $stage->startRegistrationHuman ?> <?= $timezone ?>
							<?php if ($stage->endRegistration) { ?>
                                <br>
                                Завершение регистрации: <?= $stage->endRegistrationHuman ?> <?= $timezone ?>
							<?php } ?>
                        </p>
					<?php } else { ?>
                        <p>Регистрация на этап завершена</p>
					<?php } ?>
				<?php } ?>
				<?php if ($stage->documentId) { ?>
                    <div class="regulations">
                        Регламент: <?= Html::a($stage->document->title, ['/base/download', 'id' => $stage->documentId]) ?>
                    </div>
				<?php } ?>
				
				<?php if ($internalClassesTitle = $championship->getInternalClassesTitle()) { ?>
                    <div>
                        Классы награждения: <?= $internalClassesTitle ?>
                    </div>
				<?php } ?>

                <div class="pb-10">
					<?= Html::a('Подробнее о чемпионате', ['/competitions/championship', 'id' => $championship->id]) ?>
                </div>
				
				<?php if ($stage->referenceTime) { ?>
                    <div>
                        Эталонное время трассы: <?= $stage->referenceTimeHuman ?>
                    </div>
				<?php } ?>
				<?php if ($stage->class) { ?>
                    <div>
                        Класс соревнования: <?= $stage->classModel->title ?>
                    </div>
				<?php } ?>
				
				<?php if ($stage->trackPhoto && $stage->trackPhotoStatus == Stage::PHOTO_PUBLISH) { ?>
                    <div class="track-photo">
                        <div class="toggle">
                            <div class="title">Посмотреть схему трассы</div>
                            <div class="toggle-content">
								<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->trackPhoto) ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
				
				<?php if ($stage->startRegistration && $time >= $stage->startRegistration
					&& (!$stage->endRegistration || $time <= $stage->endRegistration) && $stage->status != Stage::STATUS_PAST
				) { ?>
                    <div class="pt-30 enroll">
						<?php if ($stage->participantsLimit > 0) { ?>
                            <div class="warning">ОБРАТИТЕ ВНИМАНИЕ! Ваша заявка может быть отклонена по решению
                                организатора соревнований. В
                                этом случае вам придёт сообщение на почту и уведомление в личный кабинет. Заявки,
                                требующие
                                подтверждения организатора, выделены на сайте серым цветом.
                            </div>
						<?php } ?>
						<?php if (\Yii::$app->user->isGuest) { ?>
                            <a href="#" class="btn btn-dark" id="enrollFormHref">Зарегистрироваться</a>
                            <div class="enrollForm">
								<?= $this->render('_enroll', ['stage' => $stage]) ?>
                            </div>
                            <div class="enrollForm-success pt-10"></div>
						<?php } else { ?>
							<?php if ($championship->checkAccessForRegion(\Yii::$app->user->identity->regionId)) { ?>
                                <a href="#" class="btn btn-dark" data-toggle="modal"
                                   data-target="#enrollAuthorizedForm">Зарегистрироваться</a>
							<?php } else { ?>
                                Чемпионат закрыт для вашего города, регистрация невозможна.
							<?php } ?>
						<?php } ?>
                    </div>
				<?php } elseif ($stage->status == Stage::STATUS_END_REGISTRATION) { ?>
                    <div class="warning text-center">ПРЕДВАРИТЕЛЬНАЯ РЕГИСТРАЦИЯ НА ЭТАП ЗАВЕРШЕНА</div>
				<?php } ?>
				
				<?php if ($time >= $stage->startRegistration || $stage->status != Stage::STATUS_UPCOMING) { ?>

                    <div class="results pt-20">
                        <div class="pb-10">
							<?= \yii\bootstrap\Html::a('Скачать в xls', \yii\helpers\Url::to([
								'/export/export',
								'modelId' => $stage->id,
								'type'    => \champ\controllers\ExportController::TYPE_STAGE
							]), ['class' => 'btn btn-light']) ?>
                        </div>
						
						<?php if ($participantsByInternalClasses) { ?>
                            <div class="result-scheme active">
                                <div class="change-type">
                                    <a href="#" class="change-result-scheme">Посмотреть результаты по классам
                                        награждений</a>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="show-pk">
                                            Количество
                                            участников: <?= $countParticipants ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-right">
											<?php if ($sortBy) { ?>
												<?= Html::a('отсортировать по местам в классе', ['stage', 'id' => $stage->id]) ?>
											<?php } else { ?>
												<?= Html::a('отсортировать по местам вне класса', ['stage', 'id' => $stage->id, 'sortBy' => 'place']) ?>
											<?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 show-mobile text-right">
                                        Количество
                                        участников: <?= count($participantsByJapan) + count($tmpParticipants) ?>
                                    </div>
                                </div>
								<?= $this->render('_byJapan', [
									'stage'           => $stage,
									'participants'    => $participantsByJapan,
									'tmpParticipants' => $tmpParticipants
								]) ?>
                            </div>
                            <div class="result-scheme">
                                <div class="change-type">
                                    <a href="#" class="change-result-scheme">Посмотреть результаты по японской
                                        схеме</a>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="show-pk">
                                            Количество
                                            участников: <?= $countParticipants ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-right">
											<?php if ($sortBy) { ?>
												<?= Html::a('отсортировать по местам в классе', ['stage', 'id' => $stage->id, 'showByClasses' => true]) ?>
											<?php } else { ?>
												<?= Html::a('отсортировать по местам вне класса', ['stage', 'id' => $stage->id, 'sortBy' => 'place', 'showByClasses' => true]) ?>
											<?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 show-mobile text-right">
                                        Количество
                                        участников: <?= $countParticipants ?>
                                    </div>
                                </div>
								<?= $this->render('_byInternalClasses', [
									'stage'           => $stage,
									'participants'    => $participantsByInternalClasses,
									'tmpParticipants' => $tmpParticipants
								]) ?>
                            </div>
						<?php } else { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="show-pk">
                                        Количество
                                        участников: <?= count($participantsByJapan) + count($tmpParticipants) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-right">
										<?php if ($sortBy) { ?>
											<?= Html::a('отсортировать по местам в классе', ['stage', 'id' => $stage->id]) ?>
										<?php } else { ?>
											<?= Html::a('отсортировать по местам вне класса', ['stage', 'id' => $stage->id, 'sortBy' => 'place']) ?>
										<?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 show-mobile text-right">
                                    Количество участников: <?= count($participantsByJapan) + count($tmpParticipants) ?>
                                </div>
                            </div>
							<?= $this->render('_byJapan', [
								'stage'           => $stage,
								'participants'    => $participantsByJapan,
								'tmpParticipants' => $tmpParticipants
							]) ?>
						<?php } ?>
                    </div>
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
							<?= Html::a($item->title, ['/competitions/stage', 'id' => $item->id]) ?>
                        </li>
					<?php } ?>
					<?php if ($championship->showResults) { ?>
                        <li>
							<?= Html::a('Итоги чемпионата', ['/competitions/championship-result', 'championshipId' => $stage->championshipId]) ?>
                        </li>
					<?php } ?>
                </ul>
				<?php
			}
			?>
        </div>
    </div>

<?php if (\Yii::$app->user->isGuest) {
	//$this->render('_enrollForm', ['stage' => $stage]);
} else { ?>
	<?= $this->render('_enrollFormForAuth', ['stage' => $stage]) ?>
<?php } ?>


<?php
if ($showByClasses) {
	$js = <<<EOF
$('.result-scheme').slideToggle();
EOF;
	$this->registerJs($js);
}
?>