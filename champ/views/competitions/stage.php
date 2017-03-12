<?php
use common\models\Stage;

/**
 * @var \yii\web\View        $this
 * @var \common\models\Stage $stage
 */
$time = time();
?>

<div class="title-with-bg">
	<?= $stage->championship->title ?>
</div>

<div class="pl-10">
    <h4><?= $stage->title ?>
        , <?= $stage->city->title ?> <?php if ($stage->dateOfThe) { ?>, <?= $stage->dateOfTheHuman ?><?php } ?>
        <span class="label label-success"><?= Stage::$statusesTitle[$stage->status] ?></span></h4>
	<?php if ($stage->description) { ?>
        <p><?= $stage->description ?></p>
	<?php } ?>
	<?php if ($stage->status == Stage::STATUS_UPCOMING || $stage->status == Stage::STATUS_START_REGISTRATION) { ?>
		<?php if ($stage->startRegistration) { ?>
            <p>
                Начало регистрации: <?= $stage->startRegistrationHuman ?>
				<?php if ($stage->endRegistration) { ?>
                    <br>
                    Завершение регистрации: <?= $stage->endRegistrationHuman ?>
				<?php } ?>
            </p>
		<?php } else { ?>
            <p>Регистрация на этап завершена</p>
		<?php } ?>
	<?php } ?>
    
    <?php if ($stage->trackPhoto && $stage->trackPhotoStatus == Stage::PHOTO_PUBLISH) { ?>
        <div class="track-photo">
            <div class="toggle">
                <div class="title">Посмотреть схему</div>
                <div class="toggle-content">
	                <?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->trackPhoto) ?>
                </div>
            </div>
        </div>
    <?php } ?>
	
	<?php if ($time >= $stage->startRegistration && (!$stage->endRegistration || $time <= $stage->endRegistration)) { ?>
        <div class="pt-30">
			<?php if (\Yii::$app->user->isGuest) { ?>
                <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#enrollForm">Зарегистрироваться</a>
			<?php } else { ?>
                <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#enrollAuthorizedForm">Зарегистрироваться</a>
			<?php } ?>
        </div>
	<?php } ?>
	
	<?php if ($time >= $stage->startRegistration || $stage->status != Stage::STATUS_UPCOMING) { ?>
        <div class="results pt-20">
            <div class="show-pk">
                <table class="table results">
                    <thead>
                    <tr>
                        <th><p>Место</p></th>
                        <th><p>Класс</p></th>
                        <th><p>№</p></th>
                        <th><p>Участник</p></th>
                        <th><p>Мотоцикл</p></th>
                        <th><p>Попытка</p></th>
                        <th><p>Время</p></th>
                        <th><p>Штраф</p></th>
                        <th><p>Лучшее время</p></th>
                        <th><p>Место в классе</p></th>
                        <th><p>Класс</p></th>
                        <th><p>Рейтинг</p></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
                    /** @var \common\models\Participant[] $participants */
					$participants = $stage->getParticipants()->andWhere(['status' => \common\models\Participant::STATUS_ACTIVE])
                        ->orderBy(['percent' => SORT_ASC, 'sort' => SORT_ASC, 'id' => SORT_ASC])->all();
					$place = 1;
					if ($participants) {
						foreach ($participants as $participant) {
							$athlete = $participant->athlete;
							$times = $participant->times;
							$first = null;
							if ($times) {
								$first = reset($times);
							}
							?>
                            <tr>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->place ?></td>
                                <td rowspan="<?= $stage->countRace ?>">
									<?= $participant->athleteClassId ? $participant->athleteClass->title : null ?>
                                </td>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->number ?></td>
                                <td rowspan="<?= $stage->countRace ?>"><?= $athlete->getFullName() ?>
                                    <br><?= $athlete->city->title ?></td>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->motorcycle->getFullTitle() ?></td>
								<?php if ($first) { ?>
                                    <td>1.</td>
                                    <td><?= $first->timeForHuman ?></td>
                                    <td><?= $first->fine ?></td>
								<?php } else { ?>
                                    <td>1.</td>
                                    <td></td>
                                    <td></td>
								<?php } ?>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->humanBestTime ?></td>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->placeOfClass ?></td>
                                <td rowspan="<?= $stage->countRace ?>">
		                            <?= $participant->internalClassId ? $participant->internalClass->title : null ?>
                                </td>
                                <td rowspan="<?= $stage->countRace ?>"><?= $participant->percent ?>%</td>
                            </tr>
							<?php
							$attempt = 1;
							while ($attempt++ < $stage->countRace) {
								$next = null;
								if ($times) {
									$next = next($times);
								}
								?>
                                <tr>
                                    <td><?= $attempt ?>.</td>
									<?php if ($next) { ?>
                                        <td><?= $next->timeForHuman ?></td>
                                        <td><?= $next->fine ?></td>
									<?php } else { ?>
                                        <td></td>
                                        <td></td>
									<?php } ?>
                                </tr>
								<?php
							}
							?>
						<?php }
					} else { ?>
                        <tr>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td>1.</td>
                            <td></td>
                            <td></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                            <td rowspan="<?= $stage->countRace ?>"></td>
                        </tr>
						<?php
						$attempt = 1;
						while ($attempt++ < $stage->countRace) {
							?>
                            <tr>
                                <td><?= $attempt ?>.</td>
                                <td></td>
                                <td></td>
                            </tr>
							<?php
						}
					} ?>
                    </tbody>
                </table>
            </div>

            <div class="show-mobile">
                <table class="table results">
                    <thead>
                    <tr>
                        <th>Место /<br>Место в классе</th>
                        <th>Участник</th>
                        <th>Время</th>
                        <th>Рейтинг</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$participants = $stage->activeParticipants;
					$place = 1;
					if ($participants) {
						foreach ($participants as $participant) {
							$athlete = $participant->athlete;
							$times = $participant->times;
							?>
                            <tr>
                                <td><?= $place++ ?> / <?= $participant->placeOfClass ?></td>
                                <td>
                                    №<?= $participant->number ?> <?= $athlete->getFullName() ?>
                                    <br>
                                    <small>
										<?= $athlete->city->title ?>
                                        <br>
										<?= $participant->motorcycle->getFullTitle() ?>
										<?php if ($participant->internalClassId) { ?>
                                            <br>
											<?= $participant->internalClass->title ?>
										<?php } ?>
                                    </small>
                                </td>
                                <td>
									<?php foreach ($times as $time) { ?>
										<?= $time->timeForHuman ?>
										<?php if ($time->fine) { ?>
                                            <span class="red"> +<?= $time->fine ?></span>
										<?php } ?>
                                        <br>
									<?php } ?>
									<?php if ($participant->bestTime) { ?>
                                        <span class="green"><?= $participant->humanBestTime ?></span>
                                        <span class="green fa fa-thumbs-o-up"></span>
									<?php } ?>
                                </td>
                                <td><?= $participant->percent ?>%</td>
                            </tr>
						<?php }
					} ?>
                    </tbody>
                </table>
            </div>
        </div>
	<?php } ?>
</div>

<?php if (\Yii::$app->user->isGuest) { ?>
	<?= $this->render('_enrollForm', ['stage' => $stage]) ?>
<?php } else { ?>
	<?= $this->render('_enrollFormForAuth', ['stage' => $stage]) ?>
<?php } ?>
