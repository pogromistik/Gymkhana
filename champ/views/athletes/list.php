<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                       $this
 * @var \common\models\search\AthleteSearch $searchModel
 * @var \yii\data\ActiveDataProvider        $dataProvider
 */
?>

<?php
$listView = new \yii\widgets\ListView([
	'dataProvider' => $dataProvider,
	'layout'       => "{items}\n{pager}",
	'options'      => [
		'tag' => 'tbody'
	],
	'pager'        => [
		'firstPageLabel' => '<<',
		'lastPageLabel'  => '>>',
		'prevPageLabel'  => '<',
		'nextPageLabel'  => '>',
	],
	'itemView'     => 'athleteRow'
]);
?>

<h2>Спортсмены</h2>

<?php $form = \yii\bootstrap\ActiveForm::begin([
	'id'                     => 'search',
	'method'                 => 'get',
	'enableClientValidation' => false
]); ?>
<div class="row">

    <div class="col-md-3 sol-sm-6">
		<?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'countryId',
			'data'          => \common\models\Country::getAll(true),
			'maintainOrder' => true,
			'options'       => [
				'placeholder' => 'Выберите страну...',
				'multiple'    => false,
				'id'          => 'country-id',
				'onchange'    => 'this.form.submit()'
			],
			'pluginOptions' => [
				'tags' => true
			],
		])
		?></div>
    <div class="col-md-3 sol-sm-6">
		<?= \kartik\widgets\DepDrop::widget([
			'model'          => $searchModel,
			'attribute'      => 'regionId',
			'data'           => ($searchModel->countryId !== null) ? \common\models\Region::getAll(true, $searchModel->countryId)
				: [],
			'type'           => \kartik\widgets\DepDrop::TYPE_SELECT2,
			'select2Options' => ['pluginOptions' => ['allowClear' => true, 'placeholder' => 'Выберите регион...', 'multiple' => true]],
			'pluginOptions'  => [
				'depends'     => ['country-id'],
				'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_REGION]),
				'loadingText' => 'Для выбранной страны нет городов...',
				'placeholder' => 'Выберите регион...',
			],
			'options'        => [
				'onchange' => 'this.form.submit()'
			]
		]);
		?>
		
		<?php /*
        <?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'cityId',
			'data'          => \common\models\City::getAll(true),
			'maintainOrder' => true,
			'options'       => [
				'placeholder' => 'Выберите город...',
				'multiple'    => true,
				'onchange'    => 'this.form.submit()'
			],
			'pluginOptions' => [
				'tags' => true
			],
		])
		?> */ ?></div>
    <div class="col-md-3 sol-sm-6">
		<?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'athleteClassId',
			'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()
				->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['percent' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'title'),
			'maintainOrder' => true,
			'options'       => [
				'placeholder' => 'Выберите класс...',
				'multiple'    => true,
				'onchange'    => 'this.form.submit()'
			],
			'pluginOptions' => [
				'tags' => true
			],
		])
		?>
    </div>
    <div class="col-md-3 sol-sm-6">
		<?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'id',
			'data'          => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
				return $item->lastName . ' ' . $item->firstName;
			}),
			'pluginOptions' => [
				'allowClear' => true
			],
			'options'       => [
				'placeholder' => 'Введите имя...',
				'onchange'    => 'this.form.submit()'
			]
		])
		?>
    </div>
</div>
<button type="submit" style="visibility: hidden;" title="Сохранить"></button>
<?php $form->end() ?>

<div class="athletes">
    <div class="row">
		<?php
		foreach ($dataProvider->models as $index => $model) {
			echo $listView->renderItem($model, $model->id, $index);
		}
		?>
    </div>
	<?= $listView->renderPager() ?>
</div>