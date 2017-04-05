<?php
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;

/**
 * @var \common\models\City $region
 * @var \yii\web\View       $this
 * @var                     $error
 */

$this->title = 'Добавление нового региона';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['cities']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
	<?= Html::a('Добавить страну', ['create-country'], ['class' => 'btn btn-default']) ?>
</p>

<h3>Добавить регион</h3>

<?php $form = ActiveForm::begin(['options' => ['class' => 'newCityForm']]) ?>
<?= $form->field($region, 'countryId')->widget(Select2::classname(), [
	'data'    => \common\models\Country::getAll(true),
	'options' => [
		'placeholder' => 'Выберите страну...',
		'id'          => 'country-id',
	],
]); ?>

<?= $form->field($region, 'title')->textInput(['placeholder' => 'Укажите город']) ?>

<?php if ($error) { ?>
	<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>


