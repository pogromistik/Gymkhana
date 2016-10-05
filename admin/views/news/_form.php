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
		'preset'  => 'basic'
	]) ?>

	<?= $form->field($model, 'isPublish')->checkbox() ?>

	<?php if($model->previewImage) {
		?>
		<?= Html::img(Yii::getAlias('@filesView').$model->previewImage, ['class' => 'previewImg']) ?>
		<?php
	}
	?>
	<?= $form->field($model, 'file')->fileInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
