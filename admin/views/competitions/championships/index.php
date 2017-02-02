<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Championship;
use yii\helpers\ArrayHelper;
use common\models\Year;

/**
 * @var \yii\web\View                           $this
 * @var common\models\search\ChampionshipSearch $searchModel
 * @var yii\data\ActiveDataProvider             $dataProvider
 * @var integer                                 $groupId
 */

$this->title = Championship::$groupsTitle[$groupId];
$this->params['breadcrumbs'][] = ['label' => 'Разделы чемпионатов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-index">
    <p>
		<?= Html::a('Создать чемпионат', ['create', 'groupId' => $groupId], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'title',
			[
				'attribute' => 'yearId',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'yearId',
					ArrayHelper::map(Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year'),
					['class' => 'form-control', 'prompt' => 'Укажите год']),
				'value'     => function (Championship $championship) {
					return $championship->year->year;
				}
			],
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'status', Championship::$statusesTitle,
					['class' => 'form-control', 'prompt' => 'Выберите статус']),
				'value'     => function (Championship $championship) {
					return Championship::$statusesTitle[$championship->status];
				}
			],
			[
				'attribute' => 'groupId',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'groupId', Championship::$groupsTitle,
					['class' => 'form-control', 'prompt' => 'Выберите раздел']),
				'value'     => function (Championship $championship) {
					return Championship::$groupsTitle[$championship->groupId];
				}
			],
			[
				'format' => 'raw',
				'label'  => 'Этапы',
				'value'  => function (Championship $championship) {
					$html = '';
					$stages = $championship->stages;
					if ($stages) {
						$html = '<ul>';
						foreach ($stages as $stage) {
							$title = $stage->title;
							if ($stage->dateOfThe) {
								$title .= ', ' . $stage->dateOfTheHuman;
							}
							$html .= '<li>' . Html::a($title, ['/competitions/stages/update', 'id' => $stage->id],
									['target' => '_blank']) . '</li>';
						}
						$html .= '</ul>';
					}
					
					return $html;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (Championship $championship) {
					return Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $championship->id], [
						'class' => 'btn btn-default'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (Championship $championship) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $championship->id], [
						'class' => 'btn btn-info',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (Championship $championship) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $championship->id], [
						'class' => 'btn btn-primary',
						'title' => 'Редактирование'
					]);
				}
			],
		],
	]); ?>
</div>
