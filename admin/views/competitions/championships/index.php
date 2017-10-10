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

$view = \Yii::$app->user->can('projectAdmin') ? 'update' : 'view';
?>
<div class="championship-index">
	<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
        <p>
			<?= Html::a('Создать чемпионат', ['create', 'groupId' => $groupId],
				['class' => 'btn btn-my-style btn-green']) ?>
        </p>
	<?php } ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'title', ['class' => 'form-control', 'placeholder' => 'Поиск по названию...']) . '
</div>',
				'format'    => 'raw',
				'value'     => function (Championship $championship) {
					$html = $championship->title;
					if (\Yii::$app->user->can('developer')) {
						$html = Html::a($championship->title, ['/competitions/developer/logs',
							'modelClass' => Championship::class, 'modelId' => $championship->id],
							['class' => 'dev-logs']);
					}
					if (\Yii::$app->user->can('projectAdmin') && $championship->status === Championship::STATUS_UPCOMING) {
						$html .= '<br>';
						$html .= '<a href="#" class="ajaxDelete btn btn-my-style btn-red small" ' .
							'data-id="' . $championship->id . '"  data-action="champ">удалить чемпионат</a>';
					}
					
					return $html;
				}
			],
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
				'format' => 'raw',
				'label'  => 'Этапы',
				'value'  => function (Championship $championship) use ($view) {
					$html = '';
					$stages = $championship->stages;
					if ($stages) {
						$html = '<ul>';
						foreach ($stages as $stage) {
							$title = $stage->title;
							if ($stage->dateOfThe) {
								$title .= ', ' . $stage->dateOfTheHuman;
							}
							$html .= '<li>';
							$html .= Html::a($title, ['/competitions/stages/' . $view, 'id' => $stage->id]);
							$html .= ' ';
							$html .= Html::a('<span class="fa fa-user btn btn-my-style btn-light-aquamarine small"></span>',
								['/competitions/participants/index', 'stageId' => $stage->id]);
							if (!$stage->participants && \Yii::$app->user->can('projectAdmin')) {
								$html .= ' ';
								$html .= '<a href="#" class="ajaxDelete btn btn-my-style btn-red small fa fa-remove" ' .
									'data-id="' . $stage->id . '"  data-action="stage"></a>';
							}
							$html .= '</li>';
						}
						$html .= '</ul>';
					}
					
					return $html;
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('projectAdmin'),
				'value'   => function (Championship $championship) {
					return Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $championship->id], [
						'class' => 'btn btn-my-style btn-light-green'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (Championship $championship) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $championship->id], [
						'class' => 'btn btn-my-style btn-light-blue',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('projectAdmin'),
				'value'   => function (Championship $championship) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $championship->id], [
						'class' => 'btn btn-my-style btn-blue',
						'title' => 'Редактирование'
					]);
				}
			],
		],
	]); ?>
</div>
