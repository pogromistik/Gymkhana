<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $championships
 */
?>

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
					<?php } ?>
					<?php switch ($group) {
						case \common\models\Championship::GROUPS_RUSSIA:
							/** @var \common\models\Championship $championship */
							$championship = reset($championships[$group]);
							$stages = $championship->stages;
							if (!$stages) { ?>
                                В данный момент не создано ни одного этапа для чемпионата.
								<?php
							}
							foreach ($championship->stages as $stage) {
								?>
								<?php
								$title = $stage->title . ', ' . $stage->city->title;
								if ($stage->dateOfThe) {
									$title .= $stage->dateOfTheHuman;
								}
								?>
								<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
								<?php
							}
							break;
						case \common\models\Championship::GROUPS_REGIONAL:
							foreach ($championships[$group] as $championship) {
								?>
                                <div class="title-with-bg">
									<?= $championship->title ?>
                                </div>
								<?php
								$stages = $championship->stages;
								if (!$stages) { ?>
                                    В данный момент не создано ни одного этапа для чемпионата.
									<?php
								}
								foreach ($championship->stages as $stage) {
									?>
                                    <div class="pl-10">
                                        <?php
                                        $title = $stage->title . ', ' . $stage->city->title;
                                        if ($stage->dateOfThe) {
                                            $title .= $stage->dateOfTheHuman;
                                        }
                                        ?>
										<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
                                    </div>
									<?php
								}
							}
							break;
							?>
						<?php } ?>
                </div>
            </div>
        </div>
	<?php } ?>
</div>
