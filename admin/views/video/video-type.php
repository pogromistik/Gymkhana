<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $type common\models\VideoType */

$type->isNewRecord ? $this->title = 'Добавить раздел' : $this->title = 'Редактировать раздел: ' . $type->title;
$this->params['breadcrumbs'][] = ['label' => 'Видеогалерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($type, 'title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($type, 'pictureFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

	<?= $form->field($type, 'status')->dropDownList(\common\models\VideoType::$statusesTitle) ?>

	<div class="form-group">
		<?= Html::submitButton($type->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $type->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
