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
	    <?= Html::a('Добавить город', ['create-city'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
	    <?= Html::a('Добавить страну', ['create-country'], ['class' => 'btn btn-default']) ?>
	    <?= Html::a('Добавить регион', ['create-region'], ['class' => 'btn btn-default']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'title',
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
			[
				'format' => 'raw',
				'value'  => function (\common\models\City $city) {
					return Html::a('<span class="fa fa-edit"></span>', ['city-update', 'id' => $city->id],
						['class' => 'btn btn-primary']);
				}
			]
		],
	]); ?>
</div>
