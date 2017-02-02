<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Year;
use yii\helpers\ArrayHelper;
use common\models\Championship;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model Championship */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="championship-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput(['placeholder' => 'название чемпионата, необязательное поле']) ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 3, 'placeholder' => 'краткое описание, необязательное поле']) ?>
	
	<?= $form->field($model, 'yearId')->dropDownList(ArrayHelper::map(Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year')) ?>
	
	<?= $form->field($model, 'status')->dropDownList(Championship::$statusesTitle) ?>
	
	<?= $form->field($model, 'groupId')->hiddenInput()->label(false)->error(false) ?>
	
    <?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
	<?= $form->field($model, 'regionGroupId',
		    ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Если в списке нет необходимого раздела - создайте его в форме ниже.
</div>{input}</div>'])->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => \yii\helpers\ArrayHelper::map(\common\models\RegionalGroup::find()->all(), 'id', 'title'),
		'options' => [
			'placeholder' => 'Выберите раздел. Добавить новый раздел можно ниже',
		],
	]) ?>
    <?php } ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
