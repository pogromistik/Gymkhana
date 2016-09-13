<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'previewText')->widget(CKEditor::className(), [
		'options' => ['rows' => 6],
		'preset'  => 'full'
	]) ?>

	<?= $form->field($model, 'isPublish')->checkbox() ?>

	<?= $form->field($model, 'file')->fileInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
