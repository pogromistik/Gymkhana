<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $results
 */
?>
<h2><?= \Yii::t('app', 'Чемпионаты России и мира') ?></h2>
<div class="list">
    <div class="item">
        <div class="toggle">
            <div class="background"></div>
			<?php if (!isset($results)) { ?>
                <?= \Yii::t('app', 'В данном разделе пока нет соревнований.') ?>
			<?php } else { ?>
				<?php foreach ($results as $yearId => $yearInfo) { ?>
                    <div class="title-with-bg">
						<?= $yearInfo['year'] ?>
                    </div>
					<?php if (empty($yearInfo['champs']) && empty($yearInfo['specialChamps'])) { ?>
                        <div class="pl-10">
                            <?= \Yii::t('app', 'Не создано ни одного чемпионата.') ?>
                        </div>
					<?php } else { ?>
                        <!-- Обычные чемпионаты -->
						<?php foreach ($yearInfo['champs'] as $data) { ?>
							<?php
							/** @var \common\models\Championship $champ */
							$champ = $data['championship']; ?>
                            <div class="pl-10">
								<?= Html::a($champ->getTitle(), ['/competitions/championship', 'id' => $champ->id]) ?>
								<?php
								/** @var \common\models\Stage[] $stages */
								$stages = $data['stages'];
								if (!$stages) { ?>
                                    <div class="pl-10">
                                        <?= \Yii::t('app', 'Для чемпионата пока не создано ни одного этапа.') ?>
                                    </div>
								<?php } else { ?>
                                    <div class="pl-10">
                                        <ul>
											<?php if ($champ->showResults) { ?>
												<?php if ($champ->status == \common\models\Championship::STATUS_PAST) { ?>
                                                    <li>
														<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/championship-result', 'championshipId' => $champ->id]) ?>
                                                    </li>
												<?php } else { ?>
                                                    <li><?= Html::a(\Yii::t('app', 'Предварительные итоги чемпионата'),
															['/competitions/championship-result', 'championshipId' => $champ->id, 'showAll' => 1]) ?></li>
												<?php } ?>
											<?php } ?>
											<?php foreach ($stages as $stage) { ?>
												<?php
												$title = $stage->getTitle() . ', ' . \common\helpers\TranslitHelper::translitCity($stage->city->title);
												if ($stage->dateOfThe) {
													$title .= ' ' . $stage->dateOfTheHuman;
												}
												?>
                                                <li><?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?></li>
											<?php } ?>
                                        </ul>
                                    </div>
								<?php } ?>
                            </div>
						<?php } ?>
						
                        <!-- специальные чемпионаты -->
						<?php foreach ($yearInfo['specialChamps'] as $data) { ?>
							<?php
							/** @var \common\models\SpecialChamp $champ */
							$champ = $data['championship']; ?>
                            <div class="pl-10">
								<div class="green-title">
									<?= Html::a($champ->getTitle(), ['/competitions/special-champ', 'id' => $champ->id]) ?>
                                </div>
								<?php
								/** @var \common\models\SpecialStage[] $stages */
								$stages = $data['stages'];
								if (!$stages) { ?>
                                    <div class="pl-10">
                                        <?= \Yii::t('app', 'Для чемпионата пока не создано ни одного этапа.') ?>
                                    </div>
								<?php } else { ?>
                                    <div class="pl-10">
                                        <ul>
                                            <li>
		                                        <?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/special-champ-result', 'championshipId' => $champ->id]) ?>
                                            </li>
											<?php foreach ($stages as $stage) { ?>
												<?php
												$title = $stage->getTitle();
												if ($stage->dateStart) {
													$title .= ' ' . $stage->dateStartHuman;
												}
												?>
                                                <li><?= Html::a($title, ['/competitions/special-stage', 'id' => $stage->id]) ?></li>
											<?php } ?>
                                        </ul>
                                    </div>
								<?php } ?>
                            </div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
        </div>
    </div>
</div>