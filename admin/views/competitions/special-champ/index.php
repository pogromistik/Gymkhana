<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\SpecialChamp;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SpecialChampsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Специальные чемпионаты';
?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    К специальным относятся чемпионаты, условия проведения которых в корне отличаются от стандартной системы. На данный
    момент это чемпионаты, для которых участники сами присылают время заездов.
</div>

<div class="special-champ-index">
    <p>
		<?= Html::a('Добавить чемпионат', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>
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
				'value'     => function (SpecialChamp $championship) {
					$html = $championship->title;
					if (\Yii::$app->user->can('developer')) {
						$html = Html::a($championship->title, ['/competitions/developer/logs',
							'modelClass' => SpecialChamp::class, 'modelId' => $championship->id],
							['class' => 'dev-logs']);
					}
					if (\Yii::$app->user->can('projectAdmin') && $championship->status === SpecialChamp::STATUS_UPCOMING) {
						$html .= '<br>';
						$html .= '<a href="#" class="ajaxDelete btn btn-my-style btn-red small" ' .
							'data-id="' . $championship->id . '"  data-action="special-champ">удалить чемпионат</a>';
					}
					
					return $html;
				}
			],
			[
				'attribute' => 'yearId',
				'format'    => 'raw',
				'filter'    => Html::activeDropDownList($searchModel, 'yearId',
					ArrayHelper::map(\common\models\Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year'),
					['class' => 'form-control', 'prompt' => 'Укажите год']),
				'value'     => function (SpecialChamp $championship) {
					return $championship->year->year;
				}
			],
			[
				'format' => 'raw',
				'label'  => 'Этапы',
				'value'  => function (SpecialChamp $championship) {
					$html = '';
					
					$stages = $championship->stages;
					if ($stages) {
						$html = '<ul>';
						foreach ($stages as $stage) {
							$title = $stage->title;
							$html .= '<li>';
							$html .= Html::a($title, ['/competitions/special-champ/update-stage', 'id' => $stage->id]);
							$html .= ' ';
							$html .= Html::a('<span class="fa fa-user btn btn-my-style btn-light-aquamarine small"></span>',
								['/competitions/special-champ/participants', 'stageId' => $stage->id]);
							$html .= ' ';
							$html .= '<a href="#" class="ajaxDelete btn btn-my-style btn-red small fa fa-remove" ' .
								'data-id="' . $stage->id . '"  data-action="special-stage"></a>';
							/*if (!$stage->participants && \Yii::$app->user->can('projectAdmin')) {
								$html .= ' ';
								$html .= '<a href="#" class="ajaxDelete btn btn-my-style btn-red small fa fa-remove" ' .
									'data-id="' . $stage->id . '"  data-action="stage"></a>';
							}*/
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
				'value'   => function (SpecialChamp $championship) {
					return Html::a('Добавить этап', ['/competitions/special-champ/create-stage', 'championshipId' => $championship->id], [
						'class' => 'btn btn-my-style btn-light-green'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (SpecialChamp $championship) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $championship->id], [
						'class' => 'btn btn-my-style btn-light-blue',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('projectAdmin'),
				'value'   => function (SpecialChamp $championship) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $championship->id], [
						'class' => 'btn btn-my-style btn-blue',
						'title' => 'Редактирование'
					]);
				}
			],
		],
	]); ?>
</div>
