<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MoscowPointsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Баллы за чемпионат по Московской схеме';
?>
<div class="moscow-point-index">

    <p>
		<?= Html::a('Добавить балл', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'class',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'class',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите группу...',
					]
				]),
				'value'     => function (\common\models\MoscowPoint $item) {
					return $item->classModel->title;
				}
			],
			[
				'attribute' => 'place',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'place', ['class' => 'form-control', 'placeholder' => 'Поиск по местам...']) . '
</div>',
			],
			[
				'attribute' => 'point',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'point', ['class' => 'form-control', 'placeholder' => 'Поиск по баллам...']) . '
</div>',
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\MoscowPoint $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $item->id], [
						'class' => 'btn btn-primary',
						'title' => 'Редактировать'
					]);
				}
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\MoscowPoint $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'title' => 'Удалить',
						'data'  => [
							'confirm' => 'Уверены, что хотите полностью удалить эту запись?'
						]
					]);
				}
			],
		],
	]); ?>
</div>
