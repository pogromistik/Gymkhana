<?php
use common\models\Stage;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                $this
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant[] $participantsByJapan
 * @var \common\models\Participant[] $participantsByInternalClasses
 */
$time = time();
?>

<div class="row">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <div class="title-with-bg">
			<?= $stage->championship->title ?>
        </div>

        <div class="pl-10">
            <h4><?= $stage->title ?>
                , <?= $stage->city->title ?> <?php if ($stage->dateOfThe) { ?>, <?= $stage->dateOfTheHuman ?><?php } ?>
                <span class="label label-success"><?= Stage::$statusesTitle[$stage->status] ?></span></h4>
			<?php if ($stage->description) { ?>
                <p><?= $stage->description ?></p>
			<?php } ?>
			<?php if ($stage->status == Stage::STATUS_UPCOMING || $stage->status == Stage::STATUS_START_REGISTRATION) { ?>
				<?php if ($stage->startRegistration) { ?>
                    <p>
                        Начало регистрации: <?= $stage->startRegistrationHuman ?>
						<?php if ($stage->endRegistration) { ?>
                            <br>
                            Завершение регистрации: <?= $stage->endRegistrationHuman ?>
						<?php } ?>
                    </p>
				<?php } else { ?>
                    <p>Регистрация на этап завершена</p>
				<?php } ?>
			<?php } ?>
			<?php if ($stage->documentId) { ?>
                <div class="regulations">
					<?= Html::a($stage->document->title, ['/base/download', 'id' => $stage->documentId]) ?>
                </div>
			<?php } ?>
			
			<?php if ($stage->trackPhoto && $stage->trackPhotoStatus == Stage::PHOTO_PUBLISH) { ?>
                <div class="track-photo">
                    <div class="toggle">
                        <div class="title">Посмотреть схему</div>
                        <div class="toggle-content">
							<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $stage->trackPhoto) ?>
                        </div>
                    </div>
                </div>
			<?php } ?>
			
			<?php if ($stage->startRegistration && $time >= $stage->startRegistration
				&& (!$stage->endRegistration || $time <= $stage->endRegistration)
			) { ?>
                <div class="pt-30">
					<?php if (\Yii::$app->user->isGuest) { ?>
                        <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#enrollForm">Зарегистрироваться</a>
					<?php } else { ?>
                        <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#enrollAuthorizedForm">Зарегистрироваться</a>
					<?php } ?>
                </div>
			<?php } ?>
			
			<?php if ($time >= $stage->startRegistration || $stage->status != Stage::STATUS_UPCOMING) { ?>

                <div class="results pt-20">
                    <div class="pb-10">
						<?= \yii\bootstrap\Html::a('Скачать в xls', \yii\helpers\Url::to([
							'/export/export',
							'modelId' => $stage->id,
							'type'    => \champ\controllers\ExportController::TYPE_STAGE
						]), ['class' => 'btn btn-light']) ?>
                    </div>
					
					<?php if ($participantsByInternalClasses) { ?>
                        <div class="result-scheme active">
                            <div class="change-type">
                                <a href="#" class="change-result-scheme">Посмотреть результаты по классам
                                    награждений</a>
                            </div>
							<?= $this->render('_byJapan', ['stage' => $stage, 'participants' => $participantsByJapan]) ?>
                        </div>
                        <div class="result-scheme">
                            <div class="change-type">
                                <a href="#" class="change-result-scheme">Посмотреть результаты по японской
                                    схеме</a>
                            </div>
							<?= $this->render('_byInternalClasses', ['stage' => $stage, 'participants' => $participantsByInternalClasses]) ?>
                        </div>
					<?php } else { ?>
						<?= $this->render('_byJapan', ['stage' => $stage, 'participants' => $participantsByJapan]) ?>
					<?php } ?>
                </div>
			<?php } ?>
        </div>

    </div>

    <div class="col-bg-4 col-lg-3 col-md-2 col-sm-12 list-nav">
		<?php
		$stages = $stage->championship->stages;
		if ($stages) {
			?>
            <ul>
				<?php foreach ($stages as $stage) { ?>
                    <li>
						<?= Html::a($stage->title, ['/competitions/stage', 'id' => $stage->id]) ?>
                    </li>
				<?php } ?>
            </ul>
			<?php
		}
		?>
    </div>
</div>

<?php if (\Yii::$app->user->isGuest) { ?>
	<?= $this->render('_enrollForm', ['stage' => $stage]) ?>
<?php } else { ?>
	<?= $this->render('_enrollFormForAuth', ['stage' => $stage]) ?>
<?php } ?>
