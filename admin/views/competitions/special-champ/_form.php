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
		'preset' => 'full', 'clientOptions' => ['height' => 150]
	]) ?>
	
	<?= $form->field($model, 'yearId')->dropDownList(
		ArrayHelper::map(Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year')) ?>
	
	<?= $form->field($model, 'status')->dropDownList(\common\models\SpecialChamp::$statusesTitle) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
