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
            </div>
            <div class="modal-footer">
                <div class="form-text"></div>
                <div class="button">
					<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-lg btn-block btn-dark']) ?>
                </div>
            </div>
			<?php $form->end() ?>
        </div>
    </div>
</div>