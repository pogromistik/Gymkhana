<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\HelpProject */
/* @var $page \common\models\Page */
/* @var $success integer */

$this->title = 'Контактная информация';
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
    <b>Данные о карте указываются в разделе "Дополнительно" <span class="fa fa-long-arrow-right"></span> "Контактная
        информация" </b>
</div>

<?= Collapse::widget([
	'items' => [
		[
			'label'   => 'Настройки страницы',
			'content' => $this->render('//common/_page-form', ['model' => $page])
		],
	]
]);
?>

<?php if ($success) { ?>
    <div class="alert alert-success">
        Изменения успешно сохранены
    </div>
<?php } ?>

<div class="help-project">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'text1')->textarea() ?>
	
	<?= $form->field($model, 'text2')->textarea() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>
	
	<?php if ($model->imgFolder) { ?>
		<?= FileInput::widget([
			'name'          => 'albums_photo[]',
			'options'       => [
				'multiple' => true,
				'accept'   => 'image/*',
			],
			'pluginOptions' => [
				'uploadUrl'    => Url::to(['base/upload-album-pictures', 'folder' => $model->imgFolder]),
				'maxFileCount' => 20
			]
		]);
		?>
		
		<?php
		$photos = $model->getPhotos();
		if ($photos) {
			?>
            <table class="table">
                <tbody>
				<?php foreach ($photos as $photo) { ?>
                    <tr>
                        <td><?= Html::img(Yii::getAlias('@filesView') . '/' . $model->imgFolder . '/' . $photo) ?></td>
                        <td><a href="#" data-id="<?= $model->imgFolder . '/' . $photo ?>" class="delete-album-photo">Удалить</a>
                        </td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
			<?php
		}
		?>
	<?php } ?>
    <br><br><br><br><br>
</div>
