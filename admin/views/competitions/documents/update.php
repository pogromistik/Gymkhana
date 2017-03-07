<?php

use yii\grid\GridView;
use yii\bootstrap\Html;
use dosamigos\editable\Editable;

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
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'format'    => 'raw',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'title', ['class' => 'form-control', 'placeholder' => 'Поиск по названию...']) . '
</div>',
				'value'     => function (\common\models\OverallFile $item) {
					return Editable::widget([
						'name'          => 'title',
						'value'         => $item->title,
						'url'           => '/competitions/documents/update-file',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $item->id,
							'value'     => $item->title,
							'placement' => 'right',
						]
					]);
				}
			],
			
			[
				'attribute' => 'fileName',
				'format'    => 'raw',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'fileName', ['class' => 'form-control',
                                                         'placeholder' => 'Поиск по имени файла...']) . '
</div>',
				'value'     => function (\common\models\OverallFile $item) {
					return Editable::widget([
						'name'          => 'fileName',
						'value'         => $item->fileName,
						'url'           => '/competitions/documents/update-file',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $item->id,
							'value'     => $item->fileName,
							'placement' => 'right',
						]
					]);
				}
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\OverallFile $item) {
					return Html::a('Скачать', ['download', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\OverallFile $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['remove-file', 'id' => $item->id], [
						'class'   => 'btn btn-danger removeOverallFile',
						'data-id' => $item->id
					]);
				}
			]
		],
	]); ?>
</div>
