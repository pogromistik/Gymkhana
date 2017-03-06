<?php
use common\models\Figure;

/**
 * @var \yii\web\View               $this
 * @var Figure                      $figure
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Year | null  $year
 */
$time = time();
?>

<div class="title-with-bg">
	<?= $figure->title ?>
	<?php if ($year) { ?>
        , <?= $year->year ?>
	<?php } ?>
</div>

<div class="pl-10">
	<?php if ($figure->description) { ?>
        <p><?= $figure->description ?></p>
	<?php } ?>
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
	
	<?php if ($results) { ?>
        <div class="results pt-20">
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
                            <td><?= $item->resultTimeForHuman ?></td>
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
        </div>
	<?php } ?>
</div>

<a href="<?= \yii\helpers\Url::to(['/competitions/results', 'active' => 'figures'])?>">Вернуться к фигурам</a>