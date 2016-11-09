<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Page;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
	<p>
		<?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'title',
				'format'    => 'raw',
				'value'     => function (Page $page) {
					return Html::a($page->title, ['update', 'id' => $page->id]);
				}
			],
			[
				'attribute' => 'dateUpdated',
				'format'    => 'raw',
				'value'     => function (Page $page) {
					return date("d.m.Y, H:i", $page->dateUpdated);
				}
			],
			[
				'attribute' => 'parentId',
				'format'    => 'raw',
				'value'     => function (Page $page) {
					return $page->parent ? $page->parent->title : '';
				}
			],
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'status', Page::$statusesTitle, [
					'prompt' => 'Выберите статус', 'class' => 'form-control']),
				'value'     => function (Page $page) {
					return Page::$statusesTitle[$page->status];
				}
			],
			[
				'attribute' => 'showInMenu',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'showInMenu', Page::$showTitles, [
					'prompt' => 'Выберите тип', 'class' => 'form-control']),
				'value'     => function (Page $page) {
					return Page::$showTitles[$page->showInMenu];
				}
			],
			'sort',
			'layoutId'
		],
	]); ?>
</div>
