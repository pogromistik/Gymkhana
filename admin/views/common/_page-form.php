<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

	<?php $form = ActiveForm::begin(['id' => $model->isNewRecord ? 'newPageForm' : 'pageForm']); ?>

    <?php if (!$model->isNewRecord) { ?>
	    <?= $form->field($model, 'id')->hiddenInput()->error(false)->label(false) ?>
    <?php } ?>
    
    <?= $form->field($model, 'parentId')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Page::getParents($model->id), 'id', 'title'), [
        'prompt' => 'Отсутствует'
    ]) ?>

    <?= $form->field($model, 'layoutId')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Layout::find()->all(), 'id', 'title')) ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'status')->dropDownList(\common\models\Page::$statusesTitle) ?>

	<?= $form->field($model, 'sort')->textInput() ?>

	<?= $form->field($model, 'url')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
