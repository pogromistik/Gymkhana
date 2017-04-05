<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var \common\models\Country $country
 * @var \yii\web\View       $this
 * @var                     $error
 */

$this->title = 'Добавление новой страны';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['cities']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h3>Добавить страну</h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($country, 'title')->textInput(['placeholder' => 'Укажите город']) ?>
<?= $form->field($country, 'title_en')->textInput(['placeholder' => 'Укажите название на английском языке']) ?>
<?= $form->field($country, 'title_original')->textInput(['placeholder' => 'Укажите название на оригинальном языке']) ?>

<?php if ($error) { ?>
	<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>


