<?php
/**
 * @var common\models\search\LinkSearch $searchModel
 * @var yii\data\ActiveDataProvider     $dataProvider
 */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Ссылки на соц сети';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
	<?= Html::a('Добавить ссылку', ['link-info'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],
		
		[
			'attribute' => 'title',
			'format'    => 'raw',
			'value'     => function (\common\models\Link $link) {
				return Html::a($link->title, ['link-info', 'id' => $link->id]);
			}
		],
		'link',
		'class',
		[
			'format' => 'raw',
			'value'  => function (\common\models\Link $link) {
				return Html::a('<span class="fa fa-remove btn btn-danger"></span>', ['delete-link', 'id' => $link->id], [
					'data' => [
						'confirm' => 'Вы уверены, что хотите удалить ссылку ' . $link->title . '?'
					]
				]);
			}
		]
	],
]); ?>
