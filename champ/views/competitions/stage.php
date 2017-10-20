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
 * @var array                           $needTime
 * @var \common\models\Participant[]    $outCompetitionParticipants
 * @var array | null                    $qualification
 */
$time = time();
$city = $stage->city;
if ($city->timezone) {
	$timezone = '(' . \common\helpers\TranslitHelper::translitCity($city->title) . ', UTC ' . $city->utc . ')';
} else {
	$timezone = '(' . \Yii::t('app', 'Москва') . ', UTC +3)';
}

$championship = $stage->championship;
$countParticipants = count($participantsByJapan) + count($tmpParticipants) + count($stage->outParticipants);
?>

    <div class="row stage">
        <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
            <div class="title-with-bg">
				<?= $championship->getTitle() ?>
            </div>

            <div class="pl-10">
                <h4><?= $stage->getTitle() ?>
                    , <?= \common\helpers\TranslitHelper::translitCity($stage->city->title) ?> <?php if ($stage->dateOfThe) { ?>, <?= $stage->dateOfTheHuman ?><?php } ?>
                    <span class="label <?= ($stage->status == Stage::STATUS_CANCEL) ?
						'label-danger' : 'label-success' ?>"><?= \Yii::t('app', Stage::$statusesTitle[$stage->status]) ?></span>
                </h4>
				<?php if ($stage->location) { ?>
                    <p><?= \Yii::t('app', 'Место проведения этапа: {location}', [
							'location' => $stage->location
						]) ?></p>
				<?php } ?>
				
				<?php if ($stage->description) { ?>
                    <p><?= $stage->description ?></p>
				<?php } ?>
				<?php if ($stage->status == Stage::STATUS_UPCOMING || $stage->status == Stage::STATUS_START_REGISTRATION) { ?>
					<?php if ($stage->startRegistration || $stage->endRegistration) { ?>
                        <p>
							<?php if ($stage->startRegistration) { ?>
								<?= \Yii::t('app', 'Начало регистрации:') ?> <?= $stage->startRegistrationHuman ?> <?= $timezone ?>
							<?php } ?>
							<?php if ($stage->endRegistration) { ?>
                                <br>
								<?= \Yii::t('app', 'Завершение регистрации:') ?> <?= $stage->endRegistrationHuman ?> <?= $timezone ?>
							<?php } ?>
                        </p>
					<?php } ?>
				<?php } ?>
				<?php if ($stage->documentIds) { ?>
                    <div class="regulations">
						<?= \Yii::t('app', 'Документы') ?>:
                        <ul>
							<?php foreach ($stage->getDocuments() as $document) { ?>
                                <li><?= Html::a($document->title, ['/base/download', 'id' => $document->id]) ?></li>
							<?php } ?>
                        </ul>
                    </div>
				<?php } ?>
				
				<?php if ($internalClassesTitle = $championship->getInternalClassesTitle()) { ?>
                    <div>
						<?= \Yii::t('app', 'Классы награждения: {classes}', [
							'classes' => $internalClassesTitle
						]) ?>
                    </div>
				<?php } ?>
				
				<?php if ($stage->participantsLimit) { ?>
                    <div>
						<?= \Yii::t('app', 'Допустимое количество участников в зачёте: {count}. Цифра может быть изменена организатором.',
							['count' => $stage->participantsLimit]) ?>
                    </div>
				<?php } ?>

                <div class="pb-10">
					<?= Html::a(\Yii::t('app', 'Подробнее о чемпионате'), ['/competitions/championship', 'id' => $championship->id]) ?>
                </div>
				
				<?php if ($stage->referenceTime && $stage->class && $stage->classModel->title != Stage::CLASS_UNPERCENT) { ?>
                    <div>
						<?= \Yii::t('app', 'Эталонное время трассы: {time}', ['time' => $stage->referenceTimeHuman]) ?>
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
				<?php if ($stage->class) { ?>
                    <div>
						<?php $stageClassTitle = $stage->classModel->title; ?>
                        <?= \Yii::t('app', 'Класс соревнования: {class}', ['class' => $stage->classModel->title]) ?>
						<?php if ($stageClassTitle == Stage::CLASS_UNPERCENT) { ?>
                            <div><b>
									<?= \Yii::t('app', 'Т.к. класс соревнования {classTitle}, рейтинг спортсменов и эталонное время трассы не рассчитывается', [
										'classTitle' => $stageClassTitle
									]) ?></b></div>
						<?php } ?>
                    </div>
				<?php } ?>
				
				<?php if ($stage->trackPhoto && $stage->trackPhotoStatus == Stage::PHOTO_PUBLISH) { ?>
                    <div class="track-photo">
                        <div class="toggle">
                            <div class="title"><?= \Yii::t('app', 'Посмотреть схему трассы') ?></div>
                            <div class="toggle-content">
								<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->trackPhoto) ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
				
				<?php if ($qualification && isset($qualification['figureTitles'])) { ?>
                    <div>
                        <h5><?= Html::a(\Yii::t('app', 'Нажмите, чтобы посмотреть результаты квалификации') . '
                        (' . implode($qualification['figureTitles'], ', ') . ')',
								['/competitions/qualification', 'stageId' => $stage->id],
								['class' => 'qualification-link']) ?>
                        </h5>
                    </div>
				<?php } ?>
				
				<?php if ($stage->status == Stage::STATUS_CANCEL) { ?>
                    <div class="warning">
                        <div class="text-center">
							<?= \Yii::t('app', 'ЭТАП ОТМЕНЁН') ?><br>
							<?= \Yii::t('app', 'Для уточнения подробностей обратитесь к организаторам соревнования.') ?>
                        </div>
                    </div>
				<?php } else { ?>
					<?php if ($stage->startRegistration && $time >= $stage->startRegistration
						&& (!$stage->endRegistration || $time <= $stage->endRegistration) && $stage->status != Stage::STATUS_PAST
					) { ?>
                        <div class="pt-30 enroll">
							<?php if ($stage->participantsLimit > 0) { ?>
                                <div class="warning">
									<?= \Yii::t('app', 'ОБРАТИТЕ ВНИМАНИЕ! Ваша заявка может быть отклонена по решению организатора соревнований. В этом случае вам придёт сообщение на почту и уведомление в личный кабинет. Заявки, требующие подтверждения организатора, выделены на сайте серым цветом.') ?>
                                </div>
							<?php } ?>
							<?php if (\Yii::$app->user->isGuest) { ?>
                                <a href="#" class="btn btn-dark"
                                   id="enrollFormHref"><?= \Yii::t('app', 'Зарегистрироваться') ?></a>
                                <div class="enrollForm">
									<?= $this->render('_enroll', ['stage' => $stage]) ?>
                                </div>
                                <div class="enrollForm-success pt-10"></div>
							<?php } else { ?>
								<?php if ($championship->checkAccessForRegion(\Yii::$app->user->identity->regionId)) { ?>
                                    <a href="#" class="btn btn-dark" data-toggle="modal"
                                       data-target="#enrollAuthorizedForm"><?= \Yii::t('app', 'Зарегистрироваться') ?></a>
								<?php } else { ?>
									<?= \Yii::t('app', 'Чемпионат закрыт для вашего города, регистрация невозможна.') ?>
								<?php } ?>
							<?php } ?>
                        </div>
					<?php } elseif ($stage->status == Stage::STATUS_END_REGISTRATION) { ?>
                        <div class="warning text-center text-uppercase"><?= \Yii::t('app', 'Предварительная регистрация на этап завершена') ?></div>
					<?php } ?>
					
					<?php if (($stage->startRegistration && $time >= $stage->startRegistration) || $stage->status != Stage::STATUS_UPCOMING
						|| $time >= $stage->dateOfThe
					) { ?>

                        <div class="results pt-20">
                            <div class="pb-10">
								<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Скачать в xls'), \yii\helpers\Url::to([
									'/export/export',
									'modelId' => $stage->id,
									'type'    => \champ\controllers\ExportController::TYPE_STAGE
								]), ['class' => 'btn btn-light']) ?>
                            </div>
							
							<?php if ($participantsByInternalClasses) { ?>
                                <div class="result-scheme active">
                                    <div class="change-type">
                                        <a href="#"
                                           class="change-result-scheme"><?= \Yii::t('app', 'Посмотреть результаты по классам награждений') ?></a>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="show-pk">
												<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
												<?php if ($sortBy) { ?>
													<?= Html::a(\Yii::t('app', 'отсортировать по местам в классе'),
														['stage', 'id' => $stage->id, 'addOut' => $addOut]) ?>
												<?php } else { ?>
													<?= Html::a(\Yii::t('app', 'отсортировать по местам вне класса'),
														['stage', 'id' => $stage->id, 'sortBy' => 'place', 'addOut' => $addOut]) ?>
												<?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 show-mobile text-right">
											<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                        </div>
                                    </div>
									<?= $this->render('_byJapan', [
										'stage'                      => $stage,
										'participants'               => $participantsByJapan,
										'tmpParticipants'            => $tmpParticipants,
										'outCompetitionParticipants' => $outCompetitionParticipants,
										'showByClasses'              => $showByClasses,
										'sortBy'                     => $sortBy,
										'addOut'                     => $addOut
									]) ?>
                                </div>
                                <div class="result-scheme">
                                    <div class="change-type">
                                        <a href="#"
                                           class="change-result-scheme"><?= \Yii::t('app', 'Посмотреть результаты по японской схеме') ?></a>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="show-pk">
												<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
												<?php if ($sortBy) { ?>
													<?= Html::a(\Yii::t('app', 'отсортировать по местам в классе'),
														['stage', 'id'     => $stage->id, 'showByClasses' => true,
														          'addOut' => $addOut]) ?>
												<?php } else { ?>
													<?= Html::a(\Yii::t('app', 'отсортировать по местам вне класса'), ['stage',
														'id'     => $stage->id, 'sortBy' => 'place', 'showByClasses' => true,
														'addOut' => $addOut]) ?>
												<?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 show-mobile text-right">
											<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                        </div>
                                    </div>
									<?= $this->render('_byInternalClasses', [
										'stage'                      => $stage,
										'participants'               => $participantsByInternalClasses,
										'tmpParticipants'            => $tmpParticipants,
										'outCompetitionParticipants' => $outCompetitionParticipants,
										'showByClasses'              => $showByClasses,
										'sortBy'                     => $sortBy,
										'addOut'                     => $addOut
									]) ?>
                                </div>
							<?php } else { ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="show-pk">
											<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-right">
											<?php if ($sortBy) { ?>
												<?= Html::a(\Yii::t('app', 'отсортировать по местам в классе'),
													['stage', 'id' => $stage->id, 'addOut' => $addOut]) ?>
											<?php } else { ?>
												<?= Html::a(\Yii::t('app', 'отсортировать по местам вне класса'),
													['stage', 'id' => $stage->id, 'sortBy' => 'place', 'addOut' => $addOut]) ?>
											<?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 show-mobile text-right">
										<?= \Yii::t('app', 'Количество участников: {count}', ['count' => $countParticipants]) ?>
                                    </div>
                                </div>
								<?= $this->render('_byJapan', [
									'stage'                      => $stage,
									'participants'               => $participantsByJapan,
									'tmpParticipants'            => $tmpParticipants,
									'outCompetitionParticipants' => $outCompetitionParticipants,
									'showByClasses'              => $showByClasses,
									'sortBy'                     => $sortBy,
									'addOut'                     => $addOut
								]) ?>
							<?php } ?>
                        </div>
					<?php } ?>
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
							<?= Html::a($item->getTitle(), ['/competitions/stage', 'id' => $item->id]) ?>
                        </li>
					<?php } ?>
					<?php if ($championship->showResults) { ?>
                        <li>
							<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/championship-result', 'championshipId' => $stage->championshipId]) ?>
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