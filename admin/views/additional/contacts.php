<?php
/**
 * @var \common\models\Page     $parent
 * @var \common\models\Contacts $contacts
 * @var \yii\web\View           $this
 * @var integer                 $success
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;
use dosamigos\ckeditor\CKEditor;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if ($success) { ?>
	<div class="alert alert-success">
		Изменения успешно сохранены
	</div>
<?php } ?>

<?= Collapse::widget([
	'items' => [
		[
			'label'   => 'Настройки страницы',
			'content' => $this->render('//common/_page-form', ['model' => $parent, 'onlySetting' => true])
		],
	]
]);
?>

<div class="text-block-update">
	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($contacts, 'card')->textarea() ?>

	<?= $form->field($contacts, 'content')->widget(CKEditor::className(), [
		'options' => ['id' => 'course'],
		'preset'  => 'custom',
	]) ?>

	<?= $form->field($contacts, 'phone')->textInput() ?>

	<?= $form->field($contacts, 'email')->textInput() ?>

	<?= $form->field($contacts, 'address')->textInput() ?>

	<?= $form->field($contacts, 'time')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
