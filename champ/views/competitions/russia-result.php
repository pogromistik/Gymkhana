<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $results
 */
?>
<h2>Чемпионаты России и мира</h2>
<div class="list">
    <div class="item">
        <div class="toggle">
            <div class="background"></div>
			<?php if (!isset($results)) { ?>
                В данном разделе пока нет соревнований.
			<?php } else { ?>
				<?php foreach ($results as $yearId => $yearInfo) { ?>
                    <div class="title-with-bg">
						<?= $yearInfo['year'] ?>
                    </div>
					<?php if (empty($yearInfo['champs'])) { ?>
                        <div class="pl-10">
                            Не создано ни одного чемпионата.
                        </div>
					<?php } else { ?>
                        <!-- Обычные чемпионаты -->
						<?php foreach ($yearInfo['champs'] as $data) { ?>
							<?php
							/** @var \common\models\Championship $champ */
							$champ = $data['championship']; ?>
                            <div class="pl-10">
								<?= Html::a($champ->title, ['/competitions/championship', 'id' => $champ->id]) ?>
								<?php
								/** @var \common\models\Stage[] $stages */
								$stages = $data['stages'];
								if (!$stages) { ?>
                                    <div class="pl-10">
                                        Для чемпионата пока не создано ни одного этапа.
                                    </div>
								<?php } else { ?>
                                    <div class="pl-10">
                                        <ul>
											<?php if ($champ->showResults) { ?>
												<?php if ($champ->status == \common\models\Championship::STATUS_PAST) { ?>
                                                    <li>
														<?= Html::a('Итоги чемпионата', ['/competitions/championship-result', 'championshipId' => $champ->id]) ?>
                                                    </li>
												<?php } else { ?>
                                                    <li><?= Html::a('Предварительные итоги чемпионата',
															['/competitions/championship-result', 'championshipId' => $champ->id, 'showAll' => 1]) ?></li>
												<?php } ?>
											<?php } ?>
											<?php foreach ($stages as $stage) { ?>
												<?php
												$title = $stage->title . ', ' . $stage->city->title;
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
									<?= Html::a($champ->title, ['/competitions/special-champ', 'id' => $champ->id]) ?>
                                </div>
								<?php
								/** @var \common\models\SpecialStage[] $stages */
								$stages = $data['stages'];
								if (!$stages) { ?>
                                    <div class="pl-10">
                                        Для чемпионата пока не создано ни одного этапа.
                                    </div>
								<?php } else { ?>
                                    <div class="pl-10">
                                        <ul>
                                            <li>
		                                        <?= Html::a('Итоги чемпионата', ['/competitions/spec-champ-result', 'championshipId' => $champ->id]) ?>
                                            </li>
											<?php foreach ($stages as $stage) { ?>
												<?php
												$title = $stage->title;
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