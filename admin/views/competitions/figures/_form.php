<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\Figure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="figure-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->widget(CKEditor::className(), [
		'options' => ['id' => 'newBlock'],
		'preset'  => 'basic',
	]) ?>
	
	<?= $form->field($model, 'useForClassesCalculate')->checkbox() ?>
	
	<?= $form->field($model, 'bestTimeForHuman')->widget(MaskedInput::classname(), [
		'mask'    => '99:99.99',
		'options' => [
			'id'    => 'bestTimeForHuman' . $model->id,
			'class' => 'form-control',
			'type'  => 'tel'
		]
	]) ?>
	
	<?= $form->field($model, 'bestAthlete')->textarea(['rows' => 3]) ?>
	
	<?= $form->field($model, 'bestTimeInRussiaForHuman')->widget(MaskedInput::classname(), [
		'mask'    => '99:99.99',
		'options' => [
			'id'    => 'bestTimeInRussia' . $model->id,
			'class' => 'form-control',
			'type'  => 'tel'
		]
	]) ?>
	
	<?= $form->field($model, 'bestAthleteInRussia')->textarea(['rows' => 3]) ?>
	
	<?php if ($model->picture) { ?>
        <div class="row">
            <div class="col-md-2 col-sm-4 img-in-profile">
				<?= Html::img(\Yii::getAlias('@filesView') . '/' . $model->picture) ?>
                <br>
                <a href="#" class="btn btn-warning btn-block deletePhoto" data-id="<?= $model->id ?>"
                   data-model="<?= \admin\controllers\competitions\HelpController::PHOTO_FIGURE ?>">удалить</a>
                <br>
            </div>
            <div class="col-md-10 col-sm-8">
				<?= $form->field($model, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
            </div>
        </div>
	<?php } else { ?>
		<?= $form->field($model, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
	<?php } ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
