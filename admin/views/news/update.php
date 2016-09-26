<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/**
 * @var common\models\NewsBlock $newBlock
 * @var common\models\NewsBlock $newSlider
 */

$this->title = 'Редактировать новость: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="news-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="alert alert-info"><b>Информация для списка новостей</b></div>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

	<div class="alert alert-info">
		<b>Новость</b><br>
		Новость состоит из блоков: слайдер+текст. Можно добавить только слайдер или только текст.
		Блоки сортируются по полю "сортировка" от меньшего к большему.
		На слайдер можно добавить текст, но не обязательно.
	</div>

	<?php

	foreach ($model->newsBlock as $newsBlock) {
		?>
		<?= $newsBlock->sort ?>
		<?php
		if ($newsBlock->text) {
			?>
			<?= $newsBlock->text ?>
			<?php
		}
		if ($newsBlock->newsSliders) {
			if ($newsBlock->sliderText) {
				?>
				<?= $newsBlock->sliderText ?>
				<?php
			}
			?>
			<table class="table">
				<thead>
				<tr>
					<th>Изображение</th>
					<th>Сортировка</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($newsBlock->newsSliders as $slider) {
					?>
					<tr>
						<td>
							<?= Html::img($slider->picture) ?>
						</td>
						<td><?= \dosamigos\editable\Editable::widget([
								'name'          => 'sort',
								'value'         => $slider->sort,
								'url'           => '/news/update-slider',
								'type'          => 'text',
								'mode'          => 'inline',
								'clientOptions' => [
									'pk'        => $slider->id,
									'value'     => $slider->sort,
									'placement' => 'right',
								]
							])
							?></td>
						<td>
							<?= Html::a('Удалить', ['/news/delete-slider', 'id' => $slider->id, 'modelId' => $model->id, 'action' => 'update'],
								['data' => [
									'confirm' => 'Вы уверены, что хотите удалить это изображение?',
									'method'  => 'post',
								]]) ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
		?>
		<?= Html::a('Редактировать блок', ['/news/update-block', 'id' => $newsBlock->id],
			['class' => 'btn btn-success']) ?>
	<?php } ?>

	<h3>Добавить новый блок</h3>
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?= $form->field($newBlock, 'text')->widget(CKEditor::className(), [
		'options' => ['id' => 'newBlock'],
		'preset'  => 'basic',

	]) ?>

	<?= $form->field($newBlock, 'sliderText')->textInput(['maxlength' => true]) ?>

	<?= $form->field($newBlock, 'slider[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

	<?= $form->field($newBlock, 'sort')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
