<?php
/**
 * @var \common\models\Stage            $stage
 * @var \common\models\Participant[]    $participants
 * @var \common\models\TmpParticipant[] $tmpParticipants
 * @var \common\models\Participant[]    $outCompetitionParticipants
 */
?>

<div class="show-pk">
    <table class="table results">
        <thead>
        <tr>
            <th><img src="/img/table/class.png"></th>
            <th><img src="/img/table/placeInClass.png"></th>
            <th><img src="/img/table/number.png"></th>
            <th><img src="/img/table/participant.png"></th>
            <th><img src="/img/table/motorcycle.png"></th>
            <th><img src="/img/table/attempt.png"></th>
            <th><img src="/img/table/time.png"></th>
            <th><img src="/img/table/fine.png"></th>
            <th><img src="/img/table/bestTime.png"></th>
            <th><img src="/img/table/place.png"></th>
            <th><img src="/img/table/percent.png"></th>
        </tr>
        </thead>
        <tbody>
		<?php
		$place = 1;
		$countColumns = 11;
		if ($participants) {
			foreach ($participants as $participant) {
				$athlete = $participant->athlete;
				$times = $participant->times;
				$first = null;
				if ($times) {
					$first = reset($times);
				}
				$cssClass = -1;
				if ($participant->status === \common\models\Participant::STATUS_NEED_CLARIFICATION) {
					$cssClass = 'needClarificationParticipant';
				} else {
					if ($participant->internalClassId) {
						$cssClass = $participant->internalClassId % 10;
					}
				}
				?>
                <tr class="internal-class-<?= $cssClass ?>">
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->internalClassId ? $participant->internalClass->title : '' ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
						<?= $participant->tmpPlaceInInternalClass ?: $participant->placeOfClass ?>
                    </td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->number ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
						<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                        <br><?= $athlete->city->title ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->motorcycle->getFullTitle() ?></td>
					<?php if ($first) { ?>
                        <td>1.</td>
                        <td>
							<?php if ($first->isFail) { ?>
                                <strike><?= $first->timeForHuman ?></strike>
							<?php } else { ?>
								<?= $first->timeForHuman ?>
							<?php } ?>
	                        <?php if ($first->videoLink) { ?>
                                <a href="<?= $first->videoLink ?>" target="_blank">
                                    <i class="fa fa-youtube"></i>
                                </a>
	                        <?php } ?>
                        </td>
                        <td><?= $first->fine ?></td>
					<?php } else { ?>
                        <td>1.</td>
                        <td></td>
                        <td></td>
					<?php } ?>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->humanBestTime ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->tmpPlace ?: $participant->place ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->percent ?>%
						<?php if ($participant->newAthleteClassId && $participant->newAthleteClassStatus == \common\models\Participant::NEW_CLASS_STATUS_APPROVE) { ?>
                            (<?= $participant->newAthleteClass->title ?>)
						<?php } ?>
                    </td>
                </tr>
				<?php
				$attempt = 1;
				while ($attempt++ < $stage->countRace) {
					$next = null;
					if ($times) {
						$next = next($times);
					}
					?>
                    <tr class="internal-class-<?= $cssClass ?>">
                        <td><?= $attempt ?>.</td>
						<?php if ($next) { ?>
                            <td>
								<?php if ($next->isFail) { ?>
                                    <strike><?= $next->timeForHuman ?></strike>
								<?php } else { ?>
									<?= $next->timeForHuman ?>
								<?php } ?>
	                            <?php if ($next->videoLink) { ?>
                                    <a href="<?= $next->videoLink ?>" target="_blank">
                                        <i class="fa fa-youtube"></i>
                                    </a>
	                            <?php } ?>
                            </td>
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
		} elseif (!$tmpParticipant && !$outCompetitionParticipants) { ?>
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
		}
		if ($tmpParticipants) {
			foreach ($tmpParticipants as $tmpParticipant) { ?>
                <tr class="result-needClarificationParticipant">
                    <td rowspan="<?= $stage->countRace ?>"></td>
                    <td rowspan="<?= $stage->countRace ?>"></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $tmpParticipant->number ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $tmpParticipant->lastName ?> <?= $tmpParticipant->firstName ?>
                        <br>
						<?= $tmpParticipant->city ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $tmpParticipant->motorcycleMark ?> <?= $tmpParticipant->motorcycleModel ?></td>
                    <td>1.</td>
                    <td></td>
                    <td></td>
                    <td rowspan="<?= $stage->countRace ?>"></td>
                    <td rowspan="<?= $stage->countRace ?>"></td>
                    <td rowspan="<?= $stage->countRace ?>">%</td>
                </tr>
				<?php
				$attempt = 1;
				while ($attempt++ < $stage->countRace) {
					?>
                    <tr class="result-needClarificationParticipant">
                        <td><?= $attempt ?>.</td>
                        <td></td>
                        <td></td>
                    </tr>
					<?php
				} ?>
			<?php }
		}
		?>
		<?php if ($outCompetitionParticipants && !$addOut) { ?>
            <tr>
                <td colspan="<?= $countColumns ?>" class="text-center">
                    <b>СЛЕДУЮЩИЕ УЧАСТНИКИ ЕДУТ ВНЕ ЗАЧЁТА</b>
                    <div class="small text-right">
						<?= \yii\helpers\Html::a('добавить в общий список', [
							'stage',
							'id' => $stage->id, 'sortBy' => $sortBy, 'showByClasses' => $showByClasses, 'addOut' => true]) ?>
                    </div>
                </td>
            </tr>
			<?php foreach ($outCompetitionParticipants as $outParticipant) {
				$athlete = $outParticipant->athlete;
				$times = $outParticipant->times;
				$first = null;
				if ($times) {
					$first = reset($times);
				}
				?>
                <tr>
                    <td rowspan="<?= $stage->countRace ?>"><?= $outParticipant->internalClassId ? $outParticipant->internalClass->title : null ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
                    </td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $outParticipant->number ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
						<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                        <br><?= $athlete->city->title ?></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $outParticipant->motorcycle->getFullTitle() ?></td>
					<?php if ($first) { ?>
                        <td>1.</td>
                        <td>
							<?php if ($first->isFail) { ?>
                                <strike><?= $first->timeForHuman ?></strike>
							<?php } else { ?>
								<?= $first->timeForHuman ?>
							<?php } ?>
	                        <?php if ($first->videoLink) { ?>
                                <a href="<?= $first->videoLink ?>" target="_blank">
                                    <i class="fa fa-youtube"></i>
                                </a>
	                        <?php } ?>
                        </td>
                        <td><?= $first->fine ?></td>
					<?php } else { ?>
                        <td>1.</td>
                        <td></td>
                        <td></td>
					<?php } ?>
                    <td rowspan="<?= $stage->countRace ?>"><?= $outParticipant->humanBestTime ?></td>
                    <td rowspan="<?= $stage->countRace ?>"></td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $outParticipant->percent ?>%
						<?php if ($outParticipant->newAthleteClassId && $outParticipant->newAthleteClassStatus == \common\models\Participant::NEW_CLASS_STATUS_APPROVE) { ?>
                            (<?= $outParticipant->newAthleteClass->title ?>)
						<?php } ?>
                    </td>
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
                            <td>
								<?php if ($next->isFail) { ?>
                                    <strike><?= $next->timeForHuman ?></strike>
								<?php } else { ?>
									<?= $next->timeForHuman ?>
								<?php } ?>
	                            <?php if ($next->videoLink) { ?>
                                    <a href="<?= $next->videoLink ?>" target="_blank">
                                        <i class="fa fa-youtube"></i>
                                    </a>
	                            <?php } ?>
                            </td>
                            <td><?= $next->fine ?></td>
						<?php } else { ?>
                            <td></td>
                            <td></td>
						<?php } ?>
                    </tr>
					<?php
				}
				?>
			<?php } ?>
		<?php } ?>
        </tbody>
    </table>
	<?php if ($addOut) { ?>
        <div class="small text-right">
			<?= \yii\helpers\Html::a('убрать из списка тех, кто вне зачёта', [
				'stage',
				'id' => $stage->id, 'sortBy' => $sortBy, 'showByClasses' => $showByClasses]) ?>
        </div>
	<?php } ?>
</div>

<div class="show-mobile">
    <table class="table results">
        <thead>
        <tr>
            <th>Место вне класса /<br>Место в классе</th>
            <th>Участник</th>
            <th>Время</th>
            <th>Рейтинг</th>
        </tr>
        </thead>
        <tbody>
		<?php
		$countColumns = 4;
		if ($participants) {
			foreach ($participants as $participant) {
				$athlete = $participant->athlete;
				$times = $participant->times;
				$cssClass = -1;
				if ($participant->status === \common\models\Participant::STATUS_NEED_CLARIFICATION) {
					$cssClass = 'needClarificationParticipant';
				} else {
					if ($participant->internalClassId) {
						$cssClass = $participant->internalClassId % 10;
					}
				}
				?>
                <tr class="internal-class-<?= $cssClass ?>">
                    <td><?= $participant->tmpPlace ?: $participant->place ?>
                        / <?= $participant->tmpPlaceInAthleteClass ?: $participant->placeOfClass ?></td>
                    <td>
						<?php if ($participant->number) { ?>
							<?= \yii\bootstrap\Html::a('№' . $participant->number . ' ' . $athlete->getFullName(),
								['/athletes/view', 'id' => $athlete->id]) ?>
						<?php } else { ?>
							<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
						<?php } ?>
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
	                    <?php $video = null; ?>
						<?php foreach ($times as $time) { ?>
							<?php if ($time->isFail) { ?>
                                <strike>
									<?= $time->timeForHuman ?>
									<?php if ($time->fine) { ?>
                                        <span class="red"> +<?= $time->fine ?></span>
									<?php } ?>
                                </strike>
							<?php } else { ?>
								<?= $time->timeForHuman ?>
								<?php if ($time->fine) { ?>
                                    <span class="red"> +<?= $time->fine ?></span>
								<?php } ?>
							<?php } ?>
							<?php
							if ($time->videoLink) {
								$video .= '<a href="' . $time->videoLink . '" target="_blank"><i class="fa fa-youtube"></i></a> ';
							}
							?>
                            <br>
						<?php } ?>
						<?php if ($participant->bestTime) { ?>
                            <span class="green"><?= $participant->humanBestTime ?></span>
                            <span class="green fa fa-thumbs-o-up"></span>
						<?php } ?>
	                    <?php if ($video) { ?>
                            <br><?= $video ?>
	                    <?php } ?>
                    </td>
                    <td><?= $participant->percent ?>%
						<?php if ($participant->newAthleteClassId && $participant->newAthleteClassStatus == \common\models\Participant::NEW_CLASS_STATUS_APPROVE) { ?>
                            (<?= $participant->newAthleteClass->title ?>)
						<?php } ?>
                    </td>
                </tr>
			<?php }
		} ?>
		<?php if ($tmpParticipants) {
			foreach ($tmpParticipants as $tmpParticipant) {
				?>
                <tr class="result-needClarificationParticipant">
                    <td></td>
                    <td><?= $tmpParticipant->lastName ?> <?= $tmpParticipant->firstName ?><br>
                        <small><?= $tmpParticipant->city ?><br>
							<?= $tmpParticipant->motorcycleMark ?> <?= $tmpParticipant->motorcycleModel ?></small>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
				<?php
			}
		}
		?>
		<?php if ($outCompetitionParticipants && !$addOut) { ?>
            <tr>
                <td colspan="<?= $countColumns ?>" class="text-center">
                    <b>СЛЕДУЮЩИЕ УЧАСТНИКИ ЕДУТ ВНЕ ЗАЧЁТА</b>
                    <div class="small text-right">
						<?= \yii\helpers\Html::a('добавить в общий список', [
							'stage',
							'id' => $stage->id, 'sortBy' => $sortBy, 'showByClasses' => $showByClasses, 'addOut' => true]) ?>
                    </div>
                </td>
            </tr>
			<?php foreach ($outCompetitionParticipants as $outParticipant) {
				$athlete = $outParticipant->athlete;
				$times = $outParticipant->times;
				$first = null;
				if ($times) {
					$first = reset($times);
				}
				?>
                <tr>
                    <td></td>
                    <td>
						<?php if ($outParticipant->number) { ?>
							<?= \yii\bootstrap\Html::a('№' . $outParticipant->number . ' ' . $athlete->getFullName(),
								['/athletes/view', 'id' => $athlete->id]) ?>
						<?php } else { ?>
							<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
						<?php } ?>
                        <br>
                        <small>
							<?= $athlete->city->title ?>
                            <br>
							<?= $outParticipant->motorcycle->getFullTitle() ?>
							<?php if ($outParticipant->internalClassId) { ?>
                                <br>
								<?= $outParticipant->internalClass->title ?>
							<?php } ?>
                        </small>
                    </td>
                    <td>
	                    <?php $video = null; ?>
						<?php foreach ($times as $time) { ?>
							<?php if ($time->isFail) { ?>
                                <strike>
									<?= $time->timeForHuman ?>
									<?php if ($time->fine) { ?>
                                        <span class="red"> +<?= $time->fine ?></span>
									<?php } ?>
                                </strike>
							<?php } else { ?>
								<?= $time->timeForHuman ?>
								<?php if ($time->fine) { ?>
                                    <span class="red"> +<?= $time->fine ?></span>
								<?php } ?>
							<?php } ?>
							<?php
							if ($time->videoLink) {
								$video .= '<a href="' . $time->videoLink . '" target="_blank"><i class="fa fa-youtube"></i></a> ';
							}
							?>
                            <br>
						<?php } ?>
						<?php if ($outParticipant->bestTime) { ?>
                            <span class="green"><?= $participant->humanBestTime ?></span>
                            <span class="green fa fa-thumbs-o-up"></span>
						<?php } ?>
	                    <?php if ($video) { ?>
                            <br><?= $video ?>
	                    <?php } ?>
                    </td>
                    <td><?= $outParticipant->percent ?>%
						<?php if ($outParticipant->newAthleteClassId && $outParticipant->newAthleteClassStatus == \common\models\Participant::NEW_CLASS_STATUS_APPROVE) { ?>
                            (<?= $outParticipant->newAthleteClass->title ?>)
						<?php } ?>
                    </td>
                </tr>
			<?php } ?>
		<?php } ?>
        </tbody>
    </table>
	<?php if ($addOut) { ?>
        <div class="small text-right">
			<?= \yii\helpers\Html::a('убрать из списка тех, кто вне зачёта', [
				'stage',
				'id' => $stage->id, 'sortBy' => $sortBy, 'showByClasses' => $showByClasses]) ?>
        </div>
	<?php } ?>
</div>