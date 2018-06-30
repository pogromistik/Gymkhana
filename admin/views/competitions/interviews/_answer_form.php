<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/**
 * @var \common\models\InterviewAnswer $answer
 */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($answer, 'interviewId')->hiddenInput()->error(false)->label(false) ?>

<?php if ($answer->imgPath) { ?>
	<div class="row">
		<div class="col-md-2 col-sm-4 img-in-profile">
			<?= Html::img(\Yii::getAlias('@filesView') . '/' . $answer->imgPath) ?>
		</div>
		<div class="col-md-10 col-sm-8">
			<?= $form->field($answer, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
		</div>
	</div>
<?php } else { ?>
	<?= $form->field($answer, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
<?php } ?>

<?= $form->field($answer, 'text')->textInput(['maxlength' => true]) ?>

<?= $form->field($answer, 'textEn')->textInput(['maxlength' => true]) ?>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-green']) ?>
	</div>

<?php ActiveForm::end(); ?>