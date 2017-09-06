<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                       $this
 * @var \common\models\search\AthleteSearch $searchModel
 * @var \yii\data\ActiveDataProvider        $dataProvider
 * @var int | null                          $pg
 */
$countAthletes = $dataProvider->query->count();
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

<h2 class="title">
	<?= \Yii::t('app', 'Спортсмены') ?><br>
    <small><?= Html::a(\Yii::t('app', 'Статистика по регионам'), ['/athletes/stats-by-regions']) ?></small>
</h2>

<?php $form = \yii\bootstrap\ActiveForm::begin([
	'id'                     => 'search',
	'method'                 => 'get',
	'enableClientValidation' => false
]); ?>
<div class="row">

    <div class="col-md-3 col-sm-6 col-xs-12 col-sm-pb-10 input-with-xs-pt">
		<?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'countryId',
			'data'          => \common\models\Country::getAll(true),
			'maintainOrder' => true,
			'options'       => [
				'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
				'multiple'    => false,
				'id'          => 'country-id',
				'onchange'    => 'this.form.submit()'
			],
			'pluginOptions' => [
				'tags' => true
			],
		])
		?></div>
    <div class="col-md-3 col-sm-6 col-xs-12 col-sm-pb-10 input-with-xs-pt">
		<?= \kartik\widgets\DepDrop::widget([
			'model'          => $searchModel,
			'attribute'      => 'regionId',
			'data'           => ($searchModel->countryId !== null && $searchModel->countryId != '') ? \common\models\Region::getAll(true, $searchModel->countryId)
				: [],
			'type'           => \kartik\widgets\DepDrop::TYPE_SELECT2,
			'select2Options' => ['pluginOptions' => ['allowClear' => true, 'placeholder' => \Yii::t('app', 'Выберите регион') . '...', 'multiple' => true]],
			'pluginOptions'  => [
				'depends'     => ['country-id'],
				'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_REGION]),
				'loadingText' => \Yii::t('app', 'Для выбранной страны нет городов'),
				'placeholder' => \Yii::t('app', 'Выберите регион') . '...',
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
    <div class="col-md-3 col-sm-6 col-xs-12 col-sm-pb-10 input-with-xs-pt">
		<?=
		Select2::widget([
			'model'         => $searchModel,
			'attribute'     => 'athleteClassId',
			'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()
				->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['percent' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'title'),
			'maintainOrder' => true,
			'options'       => [
				'placeholder' => \Yii::t('app', 'Выберите класс') . '...',
				'multiple'    => true,
				'onchange'    => 'this.form.submit()'
			],
			'pluginOptions' => [
				'tags' => true
			],
		])
		?>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 col-sm-pb-10 input-with-xs-pt">
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
				'placeholder' => \Yii::t('app', 'Введите имя') . '...',
				'onchange'    => 'this.form.submit()'
			]
		])
		?>
    </div>
</div>
<button type="submit" style="visibility: hidden;" title="<?= \Yii::t('app', 'Сохранить') ?>"></button>
<?php $form->end() ?>

<div class="athletes">
	<?= $listView->renderPager() ?>
    <div class="text-right">Всего спортсменов: <?= $countAthletes ?></div>
    <div class="text-right">
		<?php if ($pg) { ?>
			<?= Html::a(($countAthletes <= 500) ? \Yii::t('app', 'Вернуться к постраничной навигации') : \Yii::t('app', 'Показывать по 20 на странице'),
				['/athletes/list']) ?>
		<?php } else { ?>
			<?= Html::a(($countAthletes <= 500) ? \Yii::t('app', 'Показать всех спортсменов') : \Yii::t('app', 'Показывать по 500 на странице'),
				['/athletes/list', 'pg' => ($countAthletes <= 500) ? $countAthletes : 500]) ?>
		<?php } ?>
    </div>
    <div class="row">
		<?php
		foreach ($dataProvider->models as $index => $model) {
			echo $listView->renderItem($model, $model->id, $index);
		}
		?>
    </div>
	<?= $listView->renderPager() ?>
</div>