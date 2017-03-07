<?php
/**
 * @var \common\models\FigureTime[] $results
 */
?>

<?php if ($results) { ?>
    <div class="show-pk">
        <table class="table results">
            <thead>
            <tr>
                <th><p>Класс</p></th>
                <th><p>Участник</p></th>
                <th><p>Мотоцикл</p></th>
                <th><p>Время</p></th>
                <th><p>Штраф</p></th>
                <th><p>Итоговое время</p></th>
                <th><p>Рейтинг</p></th>
                <th><p>Переход в класс</p></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ($results as $item) {
				$athlete = $item->athlete;
				?>
                <tr>
                    <td><?= $item->athleteClassId ? $item->athleteClass->title : null ?></td>
                    <td><?= $athlete->getFullName() ?><br><?= $athlete->city->title ?></td>
                    <td><?= $item->motorcycle->getFullTitle() ?></td>
                    <td><?= $item->timeForHuman ?></td>
                    <td><?= $item->fine ?></td>
                    <td>
                        <?= $item->resultTimeForHuman ?>
	                    <?php if ($item->recordType && $item->recordStatus == \common\models\FigureTime::NEW_RECORD_APPROVE) { ?>
		                    <?= \yii\bootstrap\Html::img('/img/crown.png', [
			                    'title' => \common\models\FigureTime::$recordsTitle[$item->recordType] . '!',
			                    'alt'   => \common\models\FigureTime::$recordsTitle[$item->recordType] . '!'
		                    ]) ?>
	                    <?php } ?>
                    </td>
                    <td><?= $item->percent ?>%</td>
                    <td><?= ($item->newAthleteClassId &&
							$item->newAthleteClassStatus == \common\models\FigureTime::NEW_CLASS_STATUS_APPROVE)
							? $item->newAthleteClass->title : null ?></td>
                </tr>
			<?php }
			?>
            </tbody>
        </table>
    </div>

    <div class="show-mobile">
        <table class="table results">
            <thead>
            <tr>
                <th>Участник</th>
                <th>Время</th>
                <th>Рейтинг</th>
            </tr>
            </thead>
            <tbody>
			<?php
			if ($results) {
				foreach ($results as $item) {
					$athlete = $item->athlete;
					?>
                    <tr>
                        <td>
							<?= $athlete->getFullName() ?>
                            <br>
                            <small>
								<?= $athlete->city->title ?>
                                <br>
								<?= $item->motorcycle->getFullTitle() ?>
								<?php if ($item->athleteClassId) { ?>
                                    <br>
									<?= $item->athleteClass->title ?>
								<?php } ?>
                            </small>
                        </td>
                        <td>
							<?= $item->timeForHuman ?>
							<?php if ($item->fine) { ?>
                                <span class="red"> +<?= $item->fine ?></span>
							<?php } ?>
                            <br>
                            <span class="green"><?= $item->resultTimeForHuman ?></span>
                        </td>
                        <td>
							<?= $item->percent ?>%
							<?php if ($item->newAthleteClassId) { ?>
								<?= $item->newAthleteClass->title ?>
							<?php } ?>
                        </td>
                    </tr>
				<?php }
			} ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    Результатов не найдено
<?php } ?>
