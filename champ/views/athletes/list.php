<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AthleteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="athletes">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'label'  => 'Имя',
				'filter' => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'id',
					'data'          => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
						return $item->lastName . ' ' . $item->firstName;
					}),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Введите имя...',
					]
				]),
                'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					$title = '';
					if ($athlete->number) {
						$title = '№' . $athlete->number . ' ';
					}
					$title .= $athlete->getFullName();
					
					return Html::a($title, ['/athletes/view', 'id' => $athlete->id]);
				}
			],
			[
				'label'  => 'Техника',
				'format' => 'raw',
				'value'  => function (\common\models\Athlete $athlete) {
					/** @var \common\models\Motorcycle[] $motorcycles */
					$motorcycles = $athlete->getMotorcycles()->andWhere(['status' => \common\models\Motorcycle::STATUS_ACTIVE])->all();
					if (count($motorcycles) == 1) {
					    $motorcycle = reset($motorcycles);
					    return $motorcycle->getFullTitle();
                    }
					$html = '<ul>';
					foreach ($motorcycles as $motorcycle) {
						$html .= '<li>' . $motorcycle->getFullTitle() . '</li>';
					}
					$html .= '</ul>';
					
					return $html;
				}
			],
			[
				'attribute' => 'regionId',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'regionId',
					'data'          => \common\models\Region::getAll(true),
					'maintainOrder' => true,
					'options'       => ['placeholder' => 'Выберите регион...', 'multiple' => true],
					'pluginOptions' => [
						'tags' => true
					],
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
                        ->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['percent' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'title'),
					'maintainOrder' => true,
					'options'       => ['placeholder' => 'Выберите класс...', 'multiple' => true],
					'pluginOptions' => [
						'tags' => true
					],
				]),
				'value'     => function (\common\models\Athlete $athlete) {
					return $athlete->athleteClassId ? $athlete->athleteClass->title : '';
				}
			],
		],
	]); ?>
</div>
