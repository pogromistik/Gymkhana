<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\RegularSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Правила';
$this->params['breadcrumbs'][] = 'О проекте';
$this->params['breadcrumbs'][] = 'Правила';
?>
<div class="regular-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Добавить правило', ['create-regular'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'text',
				'format'    => 'raw',
				'value'     => function (\common\models\Regular $regular) {
					return Html::a(substr($regular->text, 0, 70) . '...', ['/about/update-regular', 'id' => $regular->id]);
				}
			],
			'sort',
			[
				'format' => 'raw',
				'value'  => function (\common\models\Regular $regular) {
					return Html::a('<i class="glyphicon glyphicon-remove"></i>', ['/about/delete-regular', 'id' => $regular->id],
						['data' => [
							'confirm' => 'Уверены, что хотите удалить это правило?',
							'method'  => 'post',
						]]);
				}
			]
		],
	]); ?>
</div>
