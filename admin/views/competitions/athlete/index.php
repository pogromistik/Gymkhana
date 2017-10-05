<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AthleteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Спортсмены';
?>
<div class="athlete-index">
    <p>
		<?= Html::a('Добавить спортсмена', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'label'  => 'Имя',
				'format' => 'raw',
				'filter' => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'firstOrLastName',
						['class' => 'form-control', 'placeholder' => 'Имя или фамилия...']) . '
</div>',
				'value'     => function (\common\models\Athlete $athlete) {
					if (\Yii::$app->user->can('developer')) {
						return Html::a($athlete->getFullName(), ['/competitions/developer/logs',
							'modelClass' => \common\models\Athlete::class,
							'modelId'    => $athlete->id]);
					}
					
					return $athlete->getFullName();
				}
			],
			[
				'attribute' => 'phone',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'phone', ['class' => 'form-control']) . '
</div>',
			],
			[
				'attribute' => 'email',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'email', ['class' => 'form-control']) . '
</div>',
			],
			[
				'attribute' => 'regionId',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'regionId',
					'data'          => $searchModel->regionId ? [$searchModel->regionId => $searchModel->region->title] : [],
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear'         => true,
						'minimumInputLength' => 3,
						'language'           => [
							'errorLoading' => new JsExpression("function () { return 'Поиск результатов...'; }"),
						],
						'ajax'               => [
							'url'      => \yii\helpers\Url::to(['/competitions/help/region-list']),
							'dataType' => 'json',
							'data'     => new JsExpression('function(params) { return {title:params.term}; }')
						],
						'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
						'templateResult'     => new JsExpression('function(city) { return city.text; }'),
						'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
					],
					'options'       => [
						'placeholder' => 'Укажите регион...',
					]
				]),
				'value'     => function (\common\models\Athlete $athlete) {
					return $athlete->region->title;
				}
			],
			[
				'attribute' => 'cityId',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'cityId',
					'data'          => $searchModel->cityId ? [$searchModel->cityId => $searchModel->city->title] : [],
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear'         => true,
						'minimumInputLength' => 3,
						'language'           => [
							'errorLoading' => new JsExpression("function () { return 'Поиск результатов...'; }"),
						],
						'ajax'               => [
							'url'      => \yii\helpers\Url::to(['/competitions/help/city-list']),
							'dataType' => 'json',
							'data'     => new JsExpression('function(params) { return {title:params.term}; }')
						],
						'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
						'templateResult'     => new JsExpression('function(city) { return city.text; }'),
						'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
					],
					'options'       => [
						'placeholder' => 'Укажите город...',
					]
				]),
				'value'     => function (\common\models\Athlete $athlete) {
					return $athlete->city->title;
				}
			],
			[
				'attribute' => 'athleteClassId',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'athleteClassId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()
						->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['id' => SORT_ASC])->all(), 'id', 'title'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите класс...',
					]
				]),
				'value'     => function (\common\models\Athlete $athlete) {
					return $athlete->athleteClassId ? $athlete->athleteClass->title : null;
				}
			],
			[
				'attribute' => 'number',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'number', ['class' => 'form-control']) . '
</div>',
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $athlete->id], [
						'class' => 'btn btn-my-style btn-light-blue',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					return \common\helpers\UserHelper::accessAverage($athlete->regionId, $athlete->creatorUserId) ?
						Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $athlete->id], [
							'class' => 'btn btn-my-style btn-blue',
							'title' => 'Редактирование'
						]) : '';
				}
			],
		],
	]); ?>
</div>
