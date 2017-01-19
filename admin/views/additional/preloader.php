<?php
/**
 * @var \common\models\Files   $file
 * @var \yii\web\View          $this
 * @var \common\models\Files[] $preloaders
 * @var integer                $success
 */
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Изображения для предзагрузки';
?>

<?php if ($success) { ?>
    <div class="alert alert-success">
        Изменения успешно сохранены
    </div>
<?php } ?>

<div class="page-form">
	
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<?= $form->field($file, 'picture[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
		<?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>

<?php if ($preloaders) { ?>
    <table class="table">
		<?php foreach ($preloaders as $picture) { ?>
            <tr>
                <td>
					<?= Html::img(Yii::getAlias('@filesView') . '/' . $picture->folder) ?>
                </td>
                <td>
                    <span class="fa fa-remove btn btn-danger removeFile" data-id="<?= $picture->id ?>"></span>
                </td>
            </tr>
		<?php } ?>
    </table>
<?php } ?>
