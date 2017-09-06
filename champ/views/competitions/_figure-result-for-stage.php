<?php
/**
 * @var \common\models\FigureTime[] $results
 */
?>

<?php if ($results) { ?>
    <?php $first = reset($results); ?>
	<div class="show-pk">
		<table class="table results">
			<thead>
			<tr>
				<th><p>#</p></th>
				<th><p><?= \Yii::t('app', 'Класс') ?></p></th>
                <th><p><?= \Yii::t('app', 'Участник') ?></p></th>
                <th><p><?= \Yii::t('app', 'Мотоцикл') ?></p></th>
                <th><p><?= \Yii::t('app', 'Время') ?></p></th>
                <th><p><?= \Yii::t('app', 'Штраф') ?></p></th>
                <th><p><?= \Yii::t('app', 'Итоговое время') ?></p></th>
                <th><p><?= \Yii::t('app', 'Рейтинг') ?></p></th>
                <th><p><?= \Yii::t('app', 'Переход в класс') ?></p></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$place = 1;
			/** @var \common\models\FigureTime $itemForTitle */
			$itemForTitle = null;
			foreach ($results as $item) {
				$athlete = $item->athlete;
				if (!$itemForTitle || $itemForTitle->figureId != $item->figureId) {
				    $itemForTitle = $item;
				    ?>
                    <tr>
                        <td colspan="9" class="text-center result-green">
                            <?= $itemForTitle->figure->title ?>
                        </td>
                    </tr>
                    <?php
                }
				?>
				<tr>
					<td><?= $place++ ?></td>
					<td><?= $item->athleteClassId ? $item->athleteClass->title : null ?></td>
					<td><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
						<br><?= $athlete->city->title ?></td>
					<td><?= $item->motorcycle->getFullTitle() ?></td>
					<td><?= $item->timeForHuman ?></td>
					<td><?= $item->fine ?></td>
					<td>
						<?= \yii\helpers\Html::a($item->resultTimeForHuman, ['/competitions/progress',
							'figureId' => $itemForTitle->figureId, 'athleteId' => $athlete->id]) ?>
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
                <th><?= \Yii::t('app', 'Участник') ?></th>
                <th><?= \Yii::t('app', 'Время') ?></th>
                <th><?= \Yii::t('app', 'Рейтинг') ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$itemForTitle = null;
			if ($results) {
				foreach ($results as $item) {
					$athlete = $item->athlete;
					if (!$itemForTitle || $itemForTitle->figureId != $item->figureId) {
						$itemForTitle = $item;
						?>
                        <tr>
                            <td colspan="3" class="text-center result-green">
								<?= $itemForTitle->figure->title ?>
                            </td>
                        </tr>
						<?php
					}
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
	                            'figureId' => $itemForTitle->figureId, 'athleteId' => $athlete->id]) ?>
                            </span>
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
<?php }