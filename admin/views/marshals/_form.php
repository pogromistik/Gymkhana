<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Marshal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marshal-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post')->textInput(['maxlength' => true]) ?>

    <div class="alert alert-info">
        Рекомендуемые пропорции 97:50
    </div>
	<?php if ($model->photo) { ?>
        <table class="table">
            <tbody>
            <tr>
                <td>
	                <?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                </td>
                <td>
	                <?= Html::img(Yii::getAlias('@filesView') . '/' . $model->photo) ?>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } else { ?>
		<?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
    <?php } ?>

    <?= $form->field($model, 'text1')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text2')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text3')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'motorcycle')->textInput(['maxlength' => true]) ?>
    
	<?php if ($model->photo) { ?>
        <table class="table">
            <tbody>
            <tr>
                <td>
	                <?= $form->field($model, 'motorFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                </td>
                <td>
					<?= Html::img(Yii::getAlias('@filesView') . '/' . $model->motorcyclePhoto) ?>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } else { ?>
		<?= $form->field($model, 'motorFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
	<?php } ?>
	
	<?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    
	<?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
