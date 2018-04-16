<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\models\Year;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\SpecialChamp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="special-champ-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->widget(CKEditor::className(), [
		'preset' => 'full', 'clientOptions' => ['height' => 150, 'filebrowserImageUploadUrl' => '/help/upload',]
	]) ?>

    <a href="#" class="btn btn-my-style btn-gray small" id="enInfo">Добавить информацию на английском</a>
    <div class="en_info">
        <small><b>Внимание! Скрытие этого блока не удаляет введённую информацию, т.е. если вы заполните поля, потом
                скроете блок и нажмёте "сохранить" - информация сохранится</b></small>
		<?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'descr_en')->widget(CKEditor::className(), [
			'preset' => 'full', 'clientOptions' => ['height' => 150]
		]) ?>
    </div>
	
	<?= $form->field($model, 'yearId')->dropDownList(
		ArrayHelper::map(Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year')) ?>
	
	<?= $form->field($model, 'status')->dropDownList(\common\models\SpecialChamp::$statusesTitle) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
