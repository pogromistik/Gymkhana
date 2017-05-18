<?php

use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */
/* @var $form yii\widgets\ActiveForm */

$document = new \common\models\OverallFile();
?>

<div class="assoc-news-form">
	
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
    <?php if (\Yii::$app->user->can('globalWorkWithCompetitions')) { ?>
	<?= $form->field($model, 'title')->textInput(['placeholder' => 'заголовок']) ?>
    <?php } ?>
	
	<?= $form->field($document, 'files[]')->fileInput(['multiple' => true]) ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
