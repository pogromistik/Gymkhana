<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Link */
/* @var $form yii\widgets\ActiveForm */
/* @var $success integer */

$this->title = $model->isNewRecord ? 'Создание ссылки' : 'Редактирование ссылки ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ссылки на соц сети', 'url' => ['links']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($success) { ?>
	<div class="alert alert-success">Изменения успешно сохранены</div>
<?php } ?>

<div class="link-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'sort')->textInput() ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>