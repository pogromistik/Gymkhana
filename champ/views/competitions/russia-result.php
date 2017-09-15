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
					<?php foreach ($yearInfo['data'] as $data) { ?>
						<?php
						/** @var \common\models\Stage[] $stages */
						$stages = $data['stages'];
						if (!$stages) { ?>
							<div class="pl-10">
								<?= \Yii::t('app', 'Для чемпионата пока не создано ни одного этапа.') ?>
							</div>
						<?php } else { ?>
							<div class="pl-10">
								<?php if ($data['showResults']) { ?>
									<?php if ($data['status'] == \common\models\Championship::STATUS_PAST) { ?>
										<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/championship-result', 'championshipId' => $yearInfo['id']]) ?>
										<br>
									<?php } else { ?>
										<?= Html::a(\Yii::t('app', 'Предварительные итоги чемпионата'),
											['/competitions/championship-result', 'championshipId' => $data['id'], 'showAll' => 1]) ?>
										<br>
									<?php } ?>
								<?php } ?>
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
				<?php }
				?>
			<?php } ?>
        </div>
    </div>
</div>