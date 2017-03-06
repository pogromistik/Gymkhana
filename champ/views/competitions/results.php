<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $results
 * @var array         $figuresArray
 * @var string | null $active
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
						<?php if (!isset($results[$group])) { ?>
                            В данном разделе пока нет соревнований.
						<?php } else { ?>
							<?php switch ($group) {
								case \common\models\Championship::GROUPS_RUSSIA:
									foreach ($results[$group] as $yearId => $yearInfo) { ?>
                                        <div class="title-with-bg">
											<?= $yearInfo['year'] ?>
                                        </div>
										<?php
										/** @var \common\models\Stage[] $stages */
										$stages = $yearInfo['stages'];
										if (!$stages) { ?>
                                            Для чемпионата пока не создано ни одного этапа.
										<?php } else { ?>
                                            <div class="pl-10">
												<?php foreach ($stages as $stage) { ?>
													<?php
													$title = $stage->title . ', ' . $stage->city->title;
													if ($stage->dateOfThe) {
														$title .= ' ' . $stage->dateOfTheHuman;
													}
													?>
													<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
                                                    <br>
												<?php } ?>
                                            </div>
										<?php } ?>
									<?php }
									break;
								case \common\models\Championship::GROUPS_REGIONAL:
									foreach ($results[$group] as $regionGroupInfo) { ?>
                                        <div class="title-with-bg">
											<?= $regionGroupInfo['title'] ?>
                                        </div>
                                        <div class="pl-10">
											<?php foreach ($regionGroupInfo['years'] as $yearId => $yearInfo) { ?>
												<?= $yearInfo['year'] ?>
												<?php
												$stages = $yearInfo['stages'];
												if (!$stages) { ?>
                                                    Для чемпионата пока не создано ни одного этапа.
												<?php } else { ?>
                                                    <div class="pl-10">
														<?php foreach ($stages as $stage) { ?>
															<?php
															$title = $stage->title . ', ' . $stage->city->title;
															if ($stage->dateOfThe) {
																$title .= ' ' . $stage->dateOfTheHuman;
															}
															?>
															<?= Html::a($title, ['/competitions/stage', 'id' => $stage->id]) ?>
                                                            <br>
														<?php } ?>
                                                    </div>
												<?php } ?>
											<?php } ?>
                                        </div>
									<?php } ?>
									<?php
									/*foreach ($championships[$group] as $championship) {
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
							*/
									break;
									?>
								<?php }
						} ?>
                    </div>
                </div>
            </div>
		<?php } ?>

        <div class="item">
            <div class="toggle">
                <div class="background"></div>
                <div class="title figure">
                    Базовые фигуры
                </div>
                <div class="info">
					<?php if (!$figuresArray) { ?>
                        В данном разделе пока нет фигур.
					<?php } else { ?>
						<?php foreach ($figuresArray as $figureData) {
							/** @var \common\models\Figure $figure */
							$figure = $figureData['figure'];
							/** @var \common\models\Year[] $years */
							$years = $figureData['years'];
							?>
                            <div class="item">
                                <div class="toggle">
                                    <div class="background"></div>
                                    <div class="title">
										<?= $figure->title ?>
                                    </div>
                                    <div class="info">
										<?php
										if (!$years) { ?>
                                            Для фигуры пока нет ни одного результата.
										<?php } else { ?>
											<?php foreach ($years as $year) { ?>
                                                <div class="pl-10">
													<?= Html::a($year->year, ['/competitions/figure', 'id' => $figure->id, 'year' => $year->year]) ?>
                                                </div>
											<?php } ?>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
						
						<?php }
					} ?>
                </div>
            </div>
        </div>
    </div>

<?php
if ($active == 'figures') {
	$js = <<<EOF
$('.figure').trigger('click');
EOF;
	$this->registerJs($js);
}
?>