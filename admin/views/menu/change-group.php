<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GroupMenu */
/* @var $form yii\widgets\ActiveForm */
/* @var $success integer */

$this->title = $model->isNewRecord ? 'Создание группы меню' : 'Редактирование группы "' . $model->title . '"';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="menu-item-form">
	
	<?php if ($success) { ?>
        <div class="alert alert-success">Изменения успешно сохранены</div>
	<?php } ?>
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	<?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
