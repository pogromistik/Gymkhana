<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AthletesClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Классы спортсменов';
?>
<div class="athletes-class-index">

    <p>
		<?= Html::a('Добавить класс', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'format'    => 'raw',
				'value'     => function (\common\models\AthletesClass $class) {
					return Editable::widget([
						'name'          => 'title',
						'value'         => $class->title,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $class->id,
							'value'     => $class->title,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'percent',
				'format'    => 'raw',
				'value'     => function (\common\models\AthletesClass $class) {
					return Editable::widget([
						'name'          => 'percent',
						'value'         => $class->percent,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $class->id,
							'value'     => $class->percent,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'coefficient',
				'format'    => 'raw',
				'value'     => function (\common\models\AthletesClass $class) {
					return Editable::widget([
						'name'          => 'coefficient',
						'value'         => $class->coefficient,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $class->id,
							'value'     => $class->coefficient,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'sort',
				'format'    => 'raw',
				'value'     => function (\common\models\AthletesClass $class) {
					return Editable::widget([
						'name'          => 'sort',
						'value'         => $class->sort,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $class->id,
							'value'     => $class->sort,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'description',
				'format'    => 'raw',
				'value'     => function (\common\models\AthletesClass $class) {
					return Editable::widget([
						'name'          => 'description',
						'value'         => $class->description,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $class->id,
							'value'     => $class->description,
							'placement' => 'right',
						]
					]);
				}
			],
        ],
    ]); ?>
</div>
