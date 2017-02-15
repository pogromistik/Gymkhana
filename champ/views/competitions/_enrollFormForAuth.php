<?php
/**
 * @var \common\models\Stage $stage
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$participant = \common\models\Participant::createForm(\Yii::$app->user->identity->id, $stage->id);
$motorcycles = \common\models\Motorcycle::find()->where(['status' => \common\models\Motorcycle::STATUS_ACTIVE])
	->andWhere(['athleteId' => \Yii::$app->user->identity->id])->all();
$athlete = \common\models\Athlete::findOne(\Yii::$app->user->identity->id);
$championship = $stage->championship;
?>

<div class="modal fade" id="enrollForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<?php $form = ActiveForm::begin(['options' => ['class' => 'newRegistration']]) ?>
            <div class="modal-body">
                <div class="alert alert-danger" style="display: none"></div>
                <div class="alert alert-success" style="display: none"></div>
				<?= $form->field($participant, 'stageId')->hiddenInput()->label(false)->error(false) ?>
				<?= $form->field($participant, 'athleteId')->hiddenInput()->label(false)->error(false) ?>
				<?= $form->field($participant, 'championshipId')->hiddenInput()->label(false)->error(false) ?>
                <h4 class="text-center">Выберите мотоцикл</h4>
				<?= $form->field($participant, 'motorcycleId')->dropDownList(
					ArrayHelper::map($motorcycles, 'id', function (\common\models\Motorcycle $motorcycle) {
						return $motorcycle->mark . ' ' . $motorcycle->model;
					}))->label(false) ?>
				<?php if (!$championship->regionId || !\Yii::$app->user->identity->number ||
					($championship->regionId != $athlete->city->regionId)
				) { ?>
                    <div class="help-for-athlete">
                        <small>
                            Выберите значение от <?= $championship->minNumber ?> до <?= $championship->maxNumber ?>
                            или оставьте поле пустым
                        </small>
                    </div>
					<?= $form->field($participant, 'number')->textInput(['placeholder' => 'номер участника'])->label(false) ?>
                    <a href="#" class="freeNumbersList" data-id = "<?= $stage->id ?>">Посмотреть список свободных номеров</a>
				<?php } ?>
            </div>
            <div class="modal-footer">
                <div class="form-text"></div>
                <div class="button">
					<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-lg btn-block btn-dark']) ?>
                </div>
                
                <div class="free-numbers text-left">
                    <hr>
                    <h4 class="text-center">Свободные номера</h4>
                    <div class="list"></div>
                </div>
            </div>
			<?php $form->end() ?>
        </div>
    </div>
</div>