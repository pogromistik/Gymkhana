<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $layout common\models\Layout */
/* @var $success string */
/* @var $form yii\widgets\ActiveForm */
use yii\bootstrap\ActiveForm;

$layout->isNewRecord ? $this->title = 'Создание шаблона' : $this->title = 'Редактирование шаблона ' . $layout->id;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['layouts']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layout-create">

	<?php if ($success) { ?>
		<div class="alert alert-success"><?= $success ?></div>
	<?php } ?>
	<div class="layout-form">

		<?php $form = ActiveForm::begin(); ?>

		<?php if ($layout->isNewRecord) { ?>
			<?= $form->field($layout, 'id')->textInput(['maxlength' => true]) ?>
		<?php } ?>

		<?= $form->field($layout, 'title')->textInput(['maxlength' => true]) ?>

		<div class="form-group">
			<?= Html::submitButton($layout->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $layout->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
