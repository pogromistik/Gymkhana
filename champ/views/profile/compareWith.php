<?php
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \common\models\Athlete $athlete
 * @var \common\models\Athlete $me
 * @var array                  $figuresStats
 * @var string | null          $year
 * @var array                  $stagesStats
 * @var int | null             $athleteId
 */
?>

    <div class="compareWith">
        <div class="pb-10">
			<?= Html::beginForm(['/stats/compare-with'], 'get', ['id' => 'compareWith']) ?>
            сравнить свои результаты с <?= Select2::widget([
				'name'          => 'athleteId',
				'value'         => $athleteId,
				'data'          => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(\Yii::$app->user->id), 'id', function (\common\models\Athlete $item) {
					return $item->lastName . ' ' . $item->firstName;
				}),
				'options'       => [
					'placeholder' => 'Выберите спортсмена...',
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			]) ?> за <?= Html::dropDownList('year', $year, ArrayHelper::map(
				\common\models\Year::findAll(['status' => \common\models\Year::STATUS_ACTIVE]), 'year', 'year'),
				['class' => 'form-control', 'prompt' => 'всё время']
			) ?>
			<?= Html::submitButton('сравнить', ['class' => 'btn btn-dark']) ?>
			<?= Html::endForm() ?>
        </div>

        <div class="alert alert-danger" style="display: none"></div>
    </div>

<?php if ($athlete) { ?>
	<?php if ($year) { ?><h3><?= $year ?> год</h3><?php } ?>

    <div class="compare-results">
        <h3>Общая информация</h3>
        <table class="table">
            <tr>
                <td><?= $me->getFullName() ?><?php if ($me->number) { ?>, №<?= $me->number ?><?php } ?></td>
                <td><?= $athlete->getFullName() ?><?php if ($athlete->number) { ?>, №<?= $athlete->number ?><?php } ?></td>
            </tr>
            <tr>
                <td><?= $me->city->title ?></td>
                <td><?= $athlete->city->title ?></td>
            </tr>
            <tr>
				<?php $meClass = false;
				$hisClass = false; ?>
				<?php if ($me->athleteClassId && $athlete->athleteClassId) {
					if ($me->athleteClass->percent < $athlete->athleteClass->percent) {
						$meClass = true;
					} elseif ($me->athleteClass->percent > $athlete->athleteClass->percent) {
						$hisClass = true;
					}
				} ?>
                <td><?= $me->athleteClassId ? $me->athleteClass->title : null ?>
					<?php if ($meClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                </td>
                <td><?= $athlete->athleteClassId ? $athlete->athleteClass->title : null ?>
					<?php if ($hisClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                </td>
            </tr>
        </table>

        <h3>Фигуры</h3>
		<?php if (!$figuresStats) { ?>
            Нет фигур, которые пробовали проехать оба спортсмена
		<?php } else { ?>
            <table class="table">
                <tr>
                    <td><?= $me->getFullName() ?></td>
                    <td><?= $athlete->getFullName() ?></td>
                </tr>
				<?php foreach ($figuresStats as $data) {
					/** @var \common\models\FigureTime $meResult */
					$meResult = $data['me'];
					/** @var \common\models\FigureTime $hisResult */
					$hisResult = $data['his'];
					$meClass = false;
					$hisClass = false;
					if ($meResult->resultTime < $hisResult->resultTime) {
						$meClass = true;
					} elseif ($meResult->resultTime > $hisResult->resultTime) {
						$hisClass = true;
					}
					?>
                    <tr class="figure-title">
                        <td colspan="2"><?= $data['figure']->title ?></td>
                    </tr>
                    <tr>
                        <td><?= $meResult->resultTimeForHuman ?>
                            <span class="small">(<?= $meResult->timeForHuman ?> + <?= $meResult->fine ?>)</span>
							<?php if ($meClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                        </td>
                        <td><?= $hisResult->resultTimeForHuman ?>
                            <span class="small">(<?= $hisResult->timeForHuman ?> + <?= $hisResult->fine ?>)</span>
							<?php if ($hisClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                        </td>
                    </tr>
				<?php } ?>
            </table>
		<?php } ?>

        <h3>Этапы</h3>
		<?php if (!$stagesStats) { ?>
            Нет этапов, в которых приняли участие оба спортсмена
		<?php } else { ?>
            <table class="table">
                <tr>
                    <td><?= $me->getFullName() ?></td>
                    <td><?= $athlete->getFullName() ?></td>
                </tr>
				<?php foreach ($stagesStats as $data) {
					if ($data['isOneResult']) {
						/** @var \common\models\Participant $meResult */
						$meResult = $data['me'];
						/** @var \common\models\Participant $hisResult */
						$hisResult = $data['his'];
						$meClass = false;
						$hisClass = false;
						if ($meResult->humanBestTime < $hisResult->humanBestTime) {
							$meClass = true;
						} elseif ($meResult->humanBestTime > $hisResult->humanBestTime) {
							$hisClass = true;
						}
						?>
                        <tr class="figure-title">
                            <td colspan="2"><?= $data['stage']->championship->title ?>,
                                этап: <?= $data['stage']->title ?></td>
                        </tr>
                        <tr>
                            <td>
								<?= $meResult->humanBestTime ?>
								<?php if ($meClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                            </td>
                            <td><?= $hisResult->humanBestTime ?>
								<?php if ($hisClass) { ?><span class="fa fa-thumbs-o-up green"></span> <?php } ?>
                            </td>
                        </tr>
					<?php } else {
						/** @var \common\models\Participant[] $meResult */
						$meResult = $data['me'];
						/** @var \common\models\Participant[] $hisResult */
						$hisResult = $data['his'];
						?>
                        <tr class="figure-title">
                            <td colspan="2"><?= $data['stage']->championship->title ?>,
                                этап: <?= $data['stage']->title ?></td>
                        </tr>
                        <tr>
                            <td>
								<?php foreach ($meResult as $resultInfo) { ?>
									<?= $resultInfo->humanBestTime ?>
								<?php } ?>
                            </td>
                            <td>
								<?php foreach ($hisResult as $resultInfo) { ?>
									<?= $resultInfo->humanBestTime ?>
								<?php } ?>
                            </td>
                        </tr>
					<?php }
				} ?>
            </table>
		<?php } ?>
    </div>
<?php } ?>