<?php
/**
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant[] $participants
 */
?>

<div class="show-pk">
    <table class="table results">
        <thead>
        <tr>
            <th><p>Класс</p></th>
            <th><p>Место в классе</p></th>
            <th><p>№</p></th>
            <th><p>Участник</p></th>
            <th><p>Мотоцикл</p></th>
            <th><p>Попытка</p></th>
            <th><p>Время</p></th>
            <th><p>Штраф</p></th>
            <th><p>Лучшее время</p></th>
            <th><p>Место вне класса</p></th>
            <th><p>Рейтинг</p></th>
        </tr>
        </thead>
        <tbody>
		<?php
		if ($participants) {
			foreach ($participants as $participant) {
				$athlete = $participant->athlete;
				$times = $participant->times;
				$first = null;
				if ($times) {
					$first = reset($times);
				}
				$cssClass = 'default';
				$participantClass = null;
				if ($participant->athleteClassId) {
					$participantClass = $participant->athleteClass;
					if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')])) {
						$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')];
					}
				}
				?>
                <tr class="result-<?= $cssClass ?>">
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->athleteClassId ? $participant->athleteClass->title : null ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
						<?= $participant->placeOfAthleteClass ?>
                    </td>
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->number ?></td>
                    <td rowspan="<?= $stage->countRace ?>">
						<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?>
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
                    <td rowspan="<?= $stage->countRace ?>"><?= $participant->place ?></td>
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
                    <tr class="result-<?= $cssClass ?>">
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
            <th>Место вне класса /<br>Место в классе</th>
            <th>Участник</th>
            <th>Время</th>
            <th>Рейтинг</th>
        </tr>
        </thead>
        <tbody>
		<?php
		if ($participants) {
			foreach ($participants as $participant) {
				$athlete = $participant->athlete;
				$times = $participant->times;
				
				$cssClass = 'default';
				$participantClass = null;
				if ($participant->athleteClassId) {
					$participantClass = $participant->athleteClass;
					if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')])) {
						$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')];
					}
				}
				?>
                <tr class="result-<?= $cssClass ?>">
                    <td><?= $participant->place ?> / <?= $participant->placeOfAthleteClass ?></td>
                    <td>
						<?php if ($participant->number) { ?>
							<?= \yii\bootstrap\Html::a('№' . $participant->number . ' ' . $athlete->getFullName(),
								['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?>
						<?php } else { ?>
							<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?>
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