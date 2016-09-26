<?php
/**
 * @var integer                  $success
 * @var \common\models\NewsBlock $block
 * @var \yii\web\View $this
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;

$this->title = 'Редактирование блока ' . $block->id . ' для новости ' . $block->news->title;
?>

<?php if ($success) { ?>
	<div class="alert alert-success">
		Блок успешно сохранён
	</div>
<?php } ?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($block, 'text')->widget(CKEditor::className(), [
	'options' => ['id' => 'newBlock'],
	'preset'  => 'basic',

]) ?>

<?= $form->field($block, 'sliderText')->textInput(['maxlength' => true]) ?>

<?= $form->field($block, 'slider[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

<?= $form->field($block, 'sort')->textInput(['maxlength' => true]) ?>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

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
	foreach ($block->newsSliders as $slider) {
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
				<?= Html::a('Удалить', ['/news/delete-slider', 'id' => $slider->id, 'modelId' => $block->news->id, 'action' => 'update-block'],
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
