<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;

/**
 * @var \yii\web\View          $this
 * @var \common\models\Stage[] $stages
 * @var \common\models\Message $message
 * @var int                    $type
 */

$this->title = 'Отправка сообщения на почту';
?>

<div class="send-message-form">
	
	<?php if ($type == \common\models\Message::TYPE_TO_PARTICIPANTS && !$stages) { ?>
        <div class="alert alert-danger">Не найдено ни одного этапа</div>
	<?php } else { ?>

        <div class="form">
			<?php $form = ActiveForm::begin(['id' => 'sendMessagesForm']); ?>
			
			<?php if ($stages) { ?>
				<?= $form->field($message, 'stageId')->dropDownList(\yii\helpers\ArrayHelper::map(
					$stages, 'id', 'title'
				)) ?>
			<?php } ?>
			
			<?= $form->field($message, 'title')->textInput(['maxlength' => true]) ?>
			
			<?= $form->field($message, 'text')->widget(CKEditor::className(), [
				'preset' => 'full', 'clientOptions' => ['height' => 150]
			]) ?>

            <div class="alert alert-danger" style="display: none"></div>

            <div class="form-group">
				<?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
        <div class="alert alert-success" style="display: none"></div>
	<?php } ?>

</div>