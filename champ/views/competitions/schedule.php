<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $championships
 */
?>
<?php $time = time(); ?>
<h3>Расписание соревнований</h3>
<div class="list">
	<?php foreach (\common\models\Championship::$groupsTitle as $group => $title) { ?>
        <div class="item">
            <div class="toggle">
                <div class="background"></div>
                <div class="title">
					<?= $title ?>
                </div>
                <div class="info">
					<?php if (!$championships[$group]) { ?>
                        В данном разделе пока нет соревнований.
					<?php } else { ?>
						<?php switch ($group) {
							case \common\models\Championship::GROUPS_RUSSIA:
								/** @var \common\models\Championship $championship */
								$championship = reset($championships[$group]);
								/** @var \common\models\Stage[] $stages */
								$stages = $championship->getStages()
									->andWhere(['not', ['status' => \common\models\Stage::STATUS_PAST]])->all();
								if (!$stages) { ?>
                                    В данный момент нет актуальных регистраций на этап.
									<?php
								} else {
									foreach ($stages as $stage) {
										?>
										<?php
										$title = $stage->title . ', ' . $stage->city->title;
										if ($stage->dateOfThe) {
											$title .= ' ' . $stage->dateOfTheHuman;
										}
										?>
										<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
										<?php
									}
								}
								break;
							case \common\models\Championship::GROUPS_REGIONAL:
								foreach ($championships[$group] as $championship) {
									?>
                                    <div class="title-with-bg">
										<?= $championship->title ?>
                                    </div>
									<?php
									/** @var \common\models\Stage[] $stages */
									$stages = $championship->getStages()
										->andWhere(['not', ['status' => \common\models\Stage::STATUS_PAST]])->all();
									if (!$stages) { ?>
                                        В данный момент нет актуальных регистраций.
										<?php
									} else {
										foreach ($stages as $stage) {
											?>
                                            <div class="pl-10">
												<?php
												$title = $stage->title . ', ' . $stage->city->title;
												if ($stage->dateOfThe) {
													$title .= ' ' . $stage->dateOfTheHuman;
												}
												?>
												<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
                                            </div>
											<?php
										}
									}
								}
								break;
								?>
							<?php }
					} ?>
                </div>
            </div>
        </div>
	<?php } ?>
</div>
