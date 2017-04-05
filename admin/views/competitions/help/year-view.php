<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $year common\models\Year */
/* @var integer $success */

$this->title = $year->isNewRecord ? 'Добавление года' : 'Редактирование года: ' . $year->year;
$this->params['breadcrumbs'][] = ['label' => 'Года', 'url' => ['years']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="years-view">

	<?php if ($success) { ?>
		<div class="alert alert-success">
			Информация обновлена
		</div>
	<?php } ?>

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($year, 'year')->textInput(['maxlength' => true]) ?>

	<?= $form->field($year, 'status')->dropDownList(\common\models\Year::$statusesTitle) ?>

	<div class="form-group">
		<?= Html::submitButton($year->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $year->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
