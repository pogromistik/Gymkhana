<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Города';
?>
<div class="russia-index">

    <p>
	    <?= Html::a('Добавить город', ['create-city'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>
    <p>
	    <?= Html::a('Добавить страну', ['create-country'], ['class' => 'btn btn-my-style btn-default']) ?>
	    <?= Html::a('Добавить регион', ['create-region'], ['class' => 'btn btn-my-style btn-default']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'title', ['class' => 'form-control', 'placeholder' => 'Поиск по городу...']) . '
</div>',
			],
			[
				'attribute' => 'regionId',
				'format'    => 'raw',
				'value'     => function (\common\models\City $city) {
					return $city->regionId ? $city->region->title : null;
				}
			],
			[
				'attribute' => 'countryId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'countryId',
					'data'          => \common\models\Country::getAll(true),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите страну...',
					]
				]),
				'value'     => function (\common\models\City $city) {
					return $city->country->title;
				}
			],
            'utc',
			[
				'format' => 'raw',
				'value'  => function (\common\models\City $city) {
					return Html::a('<span class="fa fa-edit"></span>', ['city-update', 'id' => $city->id],
						['class' => 'btn btn-my-style btn-blue']);
				}
			]
		],
	]); ?>
</div>
