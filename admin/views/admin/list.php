<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ErrorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Критические ошибки';
?>

<div class="text-right">
	<?= Html::a('все ошибки исправлены', ['admin/fix-errors'], ['class' => 'btn btn-success']) ?>
</div>

<div class="error-index">
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (\common\models\Error $error) {
					return date('d.m.Y, H:i', $error->dateAdded);
				}
			],
			'text:ntext',
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'value'     => function (\common\models\Error $error) {
					return \common\models\Error::$statusesTitle[$error->status];
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Error $error) {
					return ($error->status !== \common\models\Error::STATUS_FIXED) ?
						Html::a('<span class="fa fa-check"></span>', ['admin/fix-errors', 'id' => $error->id], ['class' => 'btn btn-success'])
						: '';
				}
			],
		],
	]); ?>
</div>
