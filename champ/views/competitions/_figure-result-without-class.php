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
				<th><p>#</p></th>
				<th><p>Дата</p></th>
				<th><p>Класс</p></th>
				<th><p>Участник</p></th>
				<th><p>Мотоцикл</p></th>
				<th><p>Время</p></th>
				<th><p>Штраф</p></th>
				<th><p>Итоговое время</p></th>
				<th><p>Рейтинг</p></th>
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
					<td><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?><br><?= $athlete->city->title ?></td>
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
						<?php if ($item->videoLink) { ?>
							<a href="<?= $item->videoLink ?>" target="_blank">
								<i class="fa fa-youtube"></i>
							</a>
						<?php } ?>
					</td>
					<td><?= $item->percent ?>%</td>
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
							<span class="green"><?= $item->resultTimeForHuman ?></span>
							<?php if ($item->videoLink) { ?>
								<br>
								<a href="<?= $item->videoLink ?>" target="_blank">
									<i class="fa fa-youtube"></i>
								</a>
							<?php } ?>
						</td>
						<td>
							<?= $item->percent ?>%
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
