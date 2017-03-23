<?php
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use kartik\depdrop\DepDrop;

/**
 * @var \common\models\City $city
 * @var \yii\web\View       $this
 * @var                     $error
 */

$this->title = 'Добавление нового города';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['cities']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
	<?= Html::a('Добавить страну', ['create-country'], ['class' => 'btn btn-default']) ?>
	<?= Html::a('Добавить регион', ['create-region'], ['class' => 'btn btn-default']) ?>
</p>

<h3>Добавить город</h3>

<?php $form = ActiveForm::begin(['options' => ['class' => 'newCityForm']]) ?>
<?= $form->field($city, 'countryId')->widget(Select2::classname(), [
	'data'    => \common\models\Country::getAll(true),
	'options' => [
		'placeholder' => 'Выберите страну...',
		'id'          => 'country-id',
	],
]); ?>

<?php
$regions = [];
if ($city->countryId) {
    $regions = \yii\helpers\ArrayHelper::map(\common\models\Region::find()->where(['countryId' => $city->countryId])
    ->orderBy(['title' => SORT_ASC])->all(), 'id', 'title');
}?>
<?= $form->field($city, 'regionId',
	['inputTemplate' => '<div class="input-with-description"><div class="text">
 Если у города отсутствует регион - выберите Other
</div>{input}</div>'])->widget(DepDrop::classname(), [
	'data'           => $regions,
	'options'        => ['placeholder' => 'Выберите город ...'],
	'type'           => DepDrop::TYPE_SELECT2,
	'select2Options' => [
		'pluginOptions' => [
			'allowClear' => true,
		],
	],
	'pluginOptions'  => [
		'depends'     => ['country-id'],
		'url'         => \yii\helpers\Url::to(['/competitions/help/country-category',
			'type' => \admin\controllers\competitions\HelpController::TYPE_REGION]),
		'loadingText' => 'Для выбранной страны нет регионов...',
		'placeholder' => 'Выберите регион...',
	]
]);
?>

<?= $form->field($city, 'title')->textInput(['placeholder' => 'Укажите город']) ?>

<?php if ($error) { ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>


