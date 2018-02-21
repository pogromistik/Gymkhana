<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array         $results
 */
?>
    <h2><?= \Yii::t('app', 'Результаты соревнований') ?>
        <br>
        <small class="spb">
            Информация о соревнованиях в Санкт-Петербурге здесь:
            <a href="http://www.moto-gymkhana.com" target="_blank">www.moto-gymkhana.com</a>
        </small>
    </h2>
<?php if (!isset($results)) { ?>
    <?= \Yii::t('app', 'В данном разделе пока нет соревнований.') ?>
<?php } else {
	foreach ($results as $regionGroupInfo) { ?>
        <div class="list">
            <div class="item">
                <div class="toggle">
                    <div class="background"></div>
                    <div class="title">
						<?= $regionGroupInfo['title'] ?>
                    </div>
                    <div class="info">
                        <div class="pl-10">
							<?php foreach ($regionGroupInfo['years'] as $yearId => $yearInfo) { ?>
								<?= $yearInfo['year'] ?>
								<?php
								$stages = $yearInfo['stages'];
								if (!$stages) { ?>
                                    <div class="pl-10">
                                        <?= \Yii::t('app', 'Для чемпионата пока не создано ни одного этапа.') ?>
                                    </div>
								<?php } else { ?>
                                    <div class="pl-10">
										<?php if ($yearInfo['showResults']) { ?>
											<?php if ($yearInfo['status'] == \common\models\Championship::STATUS_PAST) { ?>
												<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/championship-result', 'championshipId' => $yearInfo['id']]) ?>
                                                <br>
											<?php } else { ?>
												<?= Html::a(\Yii::t('app', 'Предварительные итоги чемпионата'),
													['/competitions/championship-result', 'championshipId' => $yearInfo['id'], 'showAll' => 1]) ?>
                                                <br>
											<?php } ?>
										<?php } ?>
										<?php foreach ($stages as $stage) { ?>
											<?php
											$title = $stage->getTitle() . ', ' . \common\helpers\TranslitHelper::translitCity($stage->city->title);
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
                    </div>
                </div>
            </div>
        </div>
	<?php }
} ?>