<?php

use yii\grid\GridView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */
/* @var $success integer */
/* @var $searchModel common\models\search\OverallFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование раздела: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<?php if ($success) { ?>
	<div class="alert alert-success">Изменения успешно сохранены</div>
<?php } ?>

<div class="documents-sections-update">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

    <h3>Загруженные файлы</h3>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			
			'title:ntext',
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\OverallFile $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\OverallFile $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'data'  => [
							'confirm' => 'Уверены, что хотите заблокировать этот раздел?',
							'method'  => 'post',
						]
					]);
				}
			]
		],
	]); ?>
</div>
