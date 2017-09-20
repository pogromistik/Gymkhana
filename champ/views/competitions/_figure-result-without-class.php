<?php
/**
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Figure       $figure
 */
?>

<?php if ($results) { ?>
    <div class="show-pk">
        <table class="table results">
            <thead>
            <tr>
                <th><p>#</p></th>
                <th><p>Дата</p></th>
                <th><p>Класс</p></th>
                <th><p>Участник</p></th>
                <th><p>Мотоцикл</p></th>
                <th><p>Время</p></th>
                <th><p>Штраф</p></th>
                <th><p>Итоговое время</p></th>
	            <?php if ($figure->severalRecords) { ?>
                    <th><p>Начальный рейтинг</p></th>
                    <th><p>Актуальный рейтинг</p></th>
	            <?php } else { ?>
                    <th><p>Рейтинг</p></th>
	            <?php } ?>
            </tr>
            </thead>
            <tbody>
			<?php
			$place = 1;
			foreach ($results as $item) {
				$athlete = $item->athlete;
				?>
                <tr>
                    <td><?= $place++ ?></td>
                    <td><?= $item->dateForHuman ?></td>
                    <td><?= $item->athleteClassId ? $item->athleteClass->title : null ?></td>
                    <td><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                        <br><?= $athlete->city->title ?></td>
                    <td><?= $item->motorcycle->getFullTitle() ?></td>
                    <td><?= $item->timeForHuman ?></td>
                    <td><?= $item->fine ?></td>
                    <td>
	                    <?= \yii\helpers\Html::a($item->resultTimeForHuman, ['/competitions/progress',
		                    'figureId' => $figure->id, 'athleteId' => $athlete->id]) ?>
						<?php if ($item->recordType && $item->recordStatus == \common\models\FigureTime::NEW_RECORD_APPROVE) { ?>
							<?= \yii\bootstrap\Html::img('/img/crown.png', [
								'title' => \common\models\FigureTime::$recordsTitle[$item->recordType] . '!',
								'alt'   => \common\models\FigureTime::$recordsTitle[$item->recordType] . '!'
							]) ?>
						<?php } ?>
						<?php if ($item->videoLink) { ?>
                            <a href="<?= $item->videoLink ?>" target="_blank">
                                <i class="fa fa-youtube"></i>
                            </a>
						<?php } ?>
                    </td>
                    <?php if ($figure->severalRecords) { ?>
                        <td><abbr title="<?= $item->recordInMomentHuman ?>"><?= $item->percent ?>%</abbr></td>
                        <td><?= $item->actualPercent ?>%</td>
                    <?php } else { ?>
                        <td><?= $item->percent ?>%</td>
                    <?php } ?>
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
	            <?php if ($figure->severalRecords) { ?>
                    <th>Н.рейтинг<br>А.рейтинг</th>
	            <?php } else { ?>
                    <th>Рейтинг</th>
	            <?php } ?>
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
							<?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                            <br>
                            <small>
								<?= $athlete->city->title ?>
                                <br>
								<?= $item->motorcycle->getFullTitle() ?>
								<?php if ($item->athleteClassId) { ?>
                                    <br>
									<?= $item->athleteClass->title ?>
								<?php } ?>
                                <br>
								<?= $item->dateForHuman ?>
                            </small>
                        </td>
                        <td>
							<?= $item->timeForHuman ?>
							<?php if ($item->fine) { ?>
                                <span class="red"> +<?= $item->fine ?></span>
							<?php } ?>
                            <br>
                            <span class="green">
                            <?= \yii\helpers\Html::a($item->resultTimeForHuman, ['/competitions/progress',
	                            'figureId' => $figure->id, 'athleteId' => $athlete->id]) ?></span>
							<?php if ($item->videoLink) { ?>
                                <br>
                                <a href="<?= $item->videoLink ?>" target="_blank">
                                    <i class="fa fa-youtube"></i>
                                </a>
							<?php } ?>
                        </td>
                        <td>
							<?= $item->percent ?>%
	                        <?php if ($figure->severalRecords) { ?>
                                <br><?= $item->actualPercent ?>%
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
