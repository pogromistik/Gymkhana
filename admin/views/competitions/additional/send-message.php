<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\Select2;

/**
 * @var \yii\web\View            $this
 * @var \common\models\Stage[]   $stages
 * @var \common\models\Message   $message
 * @var \common\models\Athlete[] $athletes
 * @var int                      $type
 */

$this->title = 'Отправка сообщения на почту';
?>

<div class="send-message-form">
	
	<?php if ($type == \common\models\Message::TYPE_TO_PARTICIPANTS && !$stages) { ?>
        <div class="alert alert-danger">Не найдено ни одного этапа</div>
	<?php } elseif ($type == \common\models\Message::TYPE_TO_ATHLETES && !$athletes) { ?>
        <div class="alert alert-danger">Не найдено ни одного спортсмена</div>
	<?php } else { ?>

        <div class="form">
			<?php $form = ActiveForm::begin(['id' => 'sendMessagesForm']); ?>
			
			<?php if ($stages) { ?>
				<?= $form->field($message, 'stageId')->dropDownList(\yii\helpers\ArrayHelper::map(
					$stages, 'id', 'title'
				)) ?>
			<?php } else { ?>
				<?= $form->field($message, 'athleteIds')->widget(Select2::classname(), [
					'data'    => \yii\helpers\ArrayHelper::map($athletes, 'id', function (\common\models\Athlete $item) {
						return $item->lastName . ' ' . $item->firstName . ' (' . $item->city->title . ')';
					}),
					'options' => [
						'placeholder' => 'Выберите спортсменов...',
						'id'          => 'athletes-id',
					],
					'pluginOptions' => [
						'allowClear' => true,
                        'multiple' => true
					],
				]) ?>
            <?php } ?>
			
			<?= $form->field($message, 'title')->textInput(['maxlength' => true]) ?>
			
			<?= $form->field($message, 'text')->widget(CKEditor::className(), [
				'preset' => 'basic', 'clientOptions' => ['height' => 150]
			]) ?>

            <div class="alert alert-danger" style="display: none"></div>

            <div class="form-group">
				<?= Html::submitButton('Отправить', ['class' => 'btn btn-my-style btn-green']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
        <div class="alert alert-success" style="display: none"></div>
	<?php } ?>

</div>