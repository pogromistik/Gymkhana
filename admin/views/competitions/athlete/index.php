<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

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
				'attribute' => 'firstName',
				'filter'    => Select2::widget([
					'model'     => $searchModel,
					'attribute' => 'firstName',
					'data'      => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->all(), 'firstName', 'firstName'),
					'theme'     => Select2::THEME_BOOTSTRAP,
					'options'   => [
						'placeholder' => 'Укажите имя...',
					]
				])
			],
			[
				'attribute' => 'lastName',
				'filter'    => Select2::widget([
					'model'     => $searchModel,
					'attribute' => 'lastName',
					'data'      => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->all(), 'lastName', 'lastName'),
					'theme'     => Select2::THEME_BOOTSTRAP,
					'options'   => [
						'placeholder' => 'Укажите фамилию...',
					]
				])
			],
			'phone',
			'email:email',
			[
				'attribute' => 'cityId',
				'filter'    => Select2::widget([
					'model'     => $searchModel,
					'attribute' => 'cityId',
					'data'      => \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'title'),
					'theme'     => Select2::THEME_BOOTSTRAP,
					'options'   => [
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
					'model'     => $searchModel,
					'attribute' => 'athleteClassId',
					'data'      => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()->all(), 'id', 'title'),
					'theme'     => Select2::THEME_BOOTSTRAP,
					'options'   => [
						'placeholder' => 'Укажите класс...',
					]
				]),
				'value'     => function (\common\models\Athlete $athlete) {
					return $athlete->athleteClassId ? $athlete->athleteClass->title : null;
				}
			],
			'number',
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
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $athlete->id], [
						'class' => 'btn btn-primary',
                        'title' => 'Редактирование'
					]);
				}
			],
		],
	]); ?>
</div>
