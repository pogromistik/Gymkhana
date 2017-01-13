<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModelGroup common\models\search\GroupMenuSearch */
/* @var $dataProviderGroup yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

    <h3>Группы</h3>
    <p>
		<?= Html::a('Добавить группу', ['change-group'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProviderGroup,
		'filterModel'  => $searchModelGroup,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'title',
			'sort',
			[
				'format' => 'raw',
				'value'  => function (\common\models\GroupMenu $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['/menu/change-group', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\GroupMenu $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['/menu/delete-group', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'data'  => [
							'confirm' => 'Уверены, что хотите удалить эту группу? Все пункты, входящие в неё, так же удалятся',
							'method'  => 'post',
						]
					]);
				}
			]
		],
	]); ?>

    <h3>Пункты</h3>
    <p>
		<?= Html::a('Добавить пункт меню', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			[
				'attribute' => 'groupsMenuId',
				'format'    => 'raw',
				'value'     => function (\common\models\MenuItem $item) {
					return $item->groupsMenuId ? $item->group->title : '';
				}
			],
			[
				'attribute' => 'pageId',
				'format'    => 'raw',
				'value'     => function (\common\models\MenuItem $item) {
					return $item->pageId ? $item->page->title : '';
				}
			],
			'title',
			'sort',
			[
				'format' => 'raw',
				'value'  => function (\common\models\MenuItem $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['/menu/update', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\MenuItem $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['/menu/delete', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'data'  => [
							'confirm' => 'Уверены, что хотите удалить этот пункт?',
							'method'  => 'post',
						]
                    ]);
				}
			]
		],
	]); ?>
</div>
