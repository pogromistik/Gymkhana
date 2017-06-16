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
		<?= Html::a('Добавить спортсмена', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'lastName',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'lastName',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->orderBy(['lastName' => SORT_ASC])->all(), 'lastName', 'lastName'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите фамилию...',
					]
				])
			],
			[
				'attribute' => 'firstName',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'firstName',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->orderBy(['firstName' => SORT_ASC])->all(), 'firstName', 'firstName'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите имя...',
					]
				])
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
				'attribute' => 'hasAccount',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Athlete $athlete) {
					$html = $athlete->hasAccount ? 'Да' : 'Нет';
					if (\Yii::$app->user->can('developer') && $athlete->lastActivityDate) {
						$html .= '<br>';
						$html .= '<small class="very">' . date('d.m.y, H:i', $athlete->lastActivityDate) . '</small>';
					}
					
					return $html;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $athlete->id], [
						'class' => 'btn btn-info',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					return \common\helpers\UserHelper::accessAverage($athlete->regionId, $athlete->creatorUserId) ?
						Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $athlete->id], [
							'class' => 'btn btn-primary',
							'title' => 'Редактирование'
						]) : '';
				}
			],
		],
	]); ?>
</div>
