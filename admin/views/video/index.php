<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $types \common\models\VideoType[] */

$this->title = 'Видеогалерея';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

	<h3>
		Разделы
	</h3>
	<p>
		<?= Html::a('Добавить раздел', ['video-type'], ['class' => 'btn btn-success']) ?>
	</p>
	<table class="table">
		<?php foreach ($types as $type) { ?>
			<tr>
				<td><?= Html::a($type->title, ['/video/video-type', 'typeId' => $type->id]) ?></td>
				<td><?= \common\models\VideoType::$statusesTitle[$type->status] ?></td>
				<td><?= Html::img(Yii::getAlias('@filesView') . $type->picture) ?></td>
			</tr>
		<?php } ?>
	</table>

	<h3>Видеозаписи</h3>
	<p>
		<?= Html::a('Добавить видео', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'title',
				'format' => 'raw',
				'value' => function (\common\models\Video $video) {
					return Html::a($video->title, ['/video/view', 'id' => $video->id]);
				}
			],
			[
				'attribute' => 'typeId',
				'format' => 'raw',
				'filter' => Html::activeDropDownList($searchModel, 'typeId', \yii\helpers\ArrayHelper::map(\common\models\VideoType::getActive(), 'id', 'title'),
					['class' => 'form-control', 'prompt' => 'Выберите раздел']),
				'value' => function (\common\models\Video $video) {
					return $video->type->title;
				}
			],
		],
	]); ?>
</div>
