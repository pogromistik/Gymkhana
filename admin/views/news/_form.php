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
		'preset'  => 'advent'
	]) ?>
	
	<?= $form->field($model, 'isPublish')->checkbox() ?>
	
	<?= $form->field($model, 'secure')->checkbox() ?>

    <div class="alert alert-info">
        Пропорции изображения должны быть 1:1
    </div>
	<?php if($model->previewImage) {
		?>
        <table class="table">
            <tr>
                <td><?= $form->field($model, 'file')->fileInput() ?></td>
                <td><?= Html::img(Yii::getAlias('@filesView').$model->previewImage, ['class' => 'previewImg']) ?></td>
            </tr>
        </table>
		<?php
	} else { ?>
		<?= $form->field($model, 'file')->fileInput() ?>
    <?php } ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
