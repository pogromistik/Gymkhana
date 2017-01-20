<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Track */
/* @var $form yii\widgets\ActiveForm */

$document = new \common\models\Files();
?>

<div class="alert alert-info">
    Рекомендуемые размеры изображения - 1400x687, или хотя бы те же пропорции (16:9). Если высота будет меньше (в пропорции) - текст на
    странице может отображаться криво.
</div>

<div class="track-form">
	
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
	<?= $form->field($model, 'sort')->textInput() ?>
	
	<?php if ($model->photoPath) { ?>
        <table class="table">
            <tbody>
            <tr>
                <td>
					<?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                </td>
                <td>
					<?= Html::img(Yii::getAlias('@filesView') . $model->photoPath) ?>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } else { ?>
		<?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
	<?php } ?>
	
	<?php if ($model->documentId) { ?>
        <table class="table">
            <tbody>
            <tr>
                <td>
					<?= $form->field($document, 'file')->fileInput(['multiple' => false]) ?>
                </td>
                <td>
                    <a href="<?= Yii::getAlias('@filesView') . '/' . $model->document->folder ?>"
                       target="_blank"><?= $model->document->originalTitle ?></a>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } else { ?>
		<?= $form->field($document, 'file')->fileInput(['multiple' => false]) ?>
	<?php } ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
