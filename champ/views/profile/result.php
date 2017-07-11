<?php
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \common\models\Athlete[] $athletes
 * @var \common\models\Athlete   $me
 * @var array                    $figuresStats
 * @var string | null            $year
 * @var array                    $stagesStats
 * @var int | null               $athleteId
 * @var array                    $bestClassIds
 */

$colspan = count($athletes) + 1;
?>

<?php if ($athletes) { ?>
	<?php if ($year) { ?><h3><?= $year ?> <?= \Yii::t('app', 'год') ?></h3><?php } ?>

    <div class="compare-results">
        <h3><?= \Yii::t('app', 'Общая информация') ?></h3>
        <table class="table w-<?= $colspan ?>">
            <tr>
                <td><?= \yii\bootstrap\Html::a($me->getFullName(), ['/athletes/view', 'id' => $me->id], ['target' => '_blank']) ?>
					<?php if ($me->number) { ?>, №<?= $me->number ?><?php } ?></td>
				<?php foreach ($athletes as $athlete) { ?>
                    <td><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?>
						<?php if ($athlete->number) { ?>, №<?= $athlete->number ?><?php } ?></td>
				<?php } ?>
            </tr>
            <tr>
                <td><?= $me->city->title ?></td>
				<?php foreach ($athletes as $athlete) { ?>
                    <td><?= $athlete->city->title ?></td>
				<?php } ?>
            </tr>
            <tr>
				<?php
				$class = 'gray';
				if ($bestClassIds && in_array($me->id, $bestClassIds)) {
					$class = 'best';
				} ?>
                <td class="<?= $class ?>">
					<?= $me->athleteClassId ? $me->athleteClass->title : null ?>
                </td>
				<?php foreach ($athletes as $athlete) {
					$class = 'gray';
					if ($bestClassIds && in_array($athlete->id, $bestClassIds)) {
						$class = 'best';
					}
					?>
                    <td class="<?= $class ?>">
						<?= $athlete->athleteClassId ? $athlete->athleteClass->title : null ?>
                    </td>
				<?php } ?>
            </tr>
        </table>

        <h3><?= \Yii::t('app', 'Фигуры') ?></h3>
		<?php if (!$figuresStats) { ?>
            <div class="text"><?= \Yii::t('app', 'Нет фигур, которые пробовали проехать все спортсмены') ?></div>
		<?php } else { ?>
            <table class="table w-<?= $colspan ?>">
                <tr>
                    <td><?= $me->getFullName() ?></td>
					<?php foreach ($athletes as $athlete) { ?>
                        <td><?= $athlete->getFullName() ?></td>
					<?php } ?>
                </tr>
				<?php foreach ($figuresStats as $data) {
					/** @var \common\models\FigureTime $meResult */
					$meResult = $data['me'];
					/** @var \common\models\FigureTime $hisResult */
					$hisResults = $data['his'];
					$class = 'gray';
					if ($data['bestId'] == $me->id) {
						$class = 'best';
					}
					?>
                    <tr class="figure-title">
                        <td colspan="<?= $colspan ?>"><?= $data['figure']->title ?></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="<?= $class ?>">
								<?= $meResult->resultTimeForHuman ?>
                                <span class="small">
                                (<?= $meResult->timeForHuman ?> + <?= $meResult->fine ?>)
                            </span>
                            </div>
                            <span class="small">
                                <?= $meResult->motorcycle->getFullTitle() ?>
                            </span>
                        </td>
						<?php foreach ($hisResults as $hisResult) {
							$class = 'gray';
							if ($data['bestId'] == $hisResult->athleteId) {
								$class = 'best';
							}
							?>
                            <td>
                                <div class="<?= $class ?>">
									<?= $hisResult->resultTimeForHuman ?>
                                    <span class="small">
                                    (<?= $hisResult->timeForHuman ?> + <?= $hisResult->fine ?>)
                                </span>
                                </div>
                                <span class="small">
                                <?= $hisResult->motorcycle->getFullTitle() ?>
                            </span>
                            </td>
						<?php } ?>
                    </tr>
				<?php } ?>
            </table>
		<?php } ?>

        <h3><?= \Yii::t('app', 'Этапы') ?></h3>
		<?php if (!$stagesStats) { ?>
            <div class="text"><?= \Yii::t('app', 'Нет этапов, в которых приняли участие оба спортсмена') ?></div>
		<?php } else { ?>
            <table class="table w-<?= $colspan ?>">
                <tr>
                    <td><?= $me->getFullName() ?></td>
					<?php foreach ($athletes as $athlete) { ?>
                        <td><?= $athlete->getFullName() ?></td>
					<?php } ?>
                </tr>
				<?php foreach ($stagesStats as $data) {
					/** @var \common\models\Participant[] $meResult */
					$meResult = $data['me'];
					/** @var \common\models\Participant[] $hisResult */
					$hisResults = $data['his'];
					?>
                    <tr class="figure-title">
                        <td colspan="<?= $colspan ?>"><?= $data['stage']->championship->title ?>,
                            <?= \Yii::t('app', 'этап:') ?> <?= $data['stage']->title ?></td>
                    </tr>
                    <tr>
                        <td>
							<?php foreach ($meResult as $resultInfo) {
								$class = 'gray';
								if ($data['bestParticipantId'] == $resultInfo->id) {
									$class = 'best';
								}
								?>
                                <div class="<?= $class ?>">
									<?= $resultInfo->humanBestTime ?>
                                    (<span class="small"><?= $resultInfo->motorcycle->getFullTitle() ?></span>)
                                </div>
							<?php } ?>
                        </td>
						<?php foreach ($hisResults as $hisResult) { ?>
                        <div class="<?= $class ?>">
                            <td>
								<?php foreach ($hisResult as $resultInfo) {
								$class = 'gray';
								if ($data['bestParticipantId'] == $resultInfo->id) {
									$class = 'best';
								}
								?>
                                <div class="<?= $class ?>">
									<?= $resultInfo->humanBestTime ?>
                                    (<span class="small"><?= $resultInfo->motorcycle->getFullTitle() ?></span>)
									<?php } ?>
                                </div>
                            </td>
							<?php } ?>
                    </tr>
					<?php
				} ?>
            </table>
		<?php } ?>
    </div>
<?php } ?>