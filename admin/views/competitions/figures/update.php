<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use kartik\widgets\DatePicker;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Figure */
/* @var $success integer */
/* @var $searchModel common\models\search\FigureTimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование фигуры: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Фигуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
$newClasses = $model->getResults()->andWhere(['not', ['newAthleteClassId' => null]])
	->andWhere(['newAthleteClassStatus' => \common\models\FigureTime::NEW_CLASS_STATUS_NEED_CHECK])->all()
?>
<div class="figure-update">
	
	<?php if ($success) { ?>
        <div class="alert alert-success">
            Изменения успешно сохранены
        </div>
	<?php } ?>
	
	<?= Collapse::widget([
		'items' => [
			[
				'label'   => 'Редактировать фигуру',
				'content' => $this->render('_form', ['model' => $model])
			],
		]
	]);
	?>

    <h3>Результаты</h3>
	<?php Modal::begin([
		'header'       => '<h2>Выберите дату заездов</h2>',
		'toggleButton' => [
			'label' => 'Добавить результаты',
			'class' => 'btn btn-success'
		]
	]) ?>
	<?= \yii\bootstrap\Html::beginForm(['add-results'], 'get') ?>
	<?= Html::hiddenInput('figureId', $model->id) ?>
    <div class="row">
        <div class="col-md-4">
			<?php
			echo DatePicker::widget([
				'type'          => DatePicker::TYPE_INPUT,
				'name'          => 'date',
				'value'         => date('d.m.Y', time()),
				'language'      => 'ru',
				'options'       => ['placeholder' => 'Дата'],
				'removeButton'  => false,
				'pluginOptions' => [
					'autoclose'      => true,
					'format'         => 'dd.mm.yyyy',
					'todayHighlight' => true
				]
			]);
			?>
        </div>
        <div class="col-md-2">
			<?= Html::submitButton(\Yii::t('app', 'ОК'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
	<?= Html::endForm() ?>
	<?php Modal::end() ?>
	
	
	<?php if ($newClasses) { ?>
        <div class="text-right newClass">
            <div class="pb-10">
                <a class="btn btn-danger getRequest" href="#"
                   data-action="/competitions/figures/cancel-all-classes"
                   data-id="<?= $model->id ?>" title="Отменить">
                    Отменить все новые классы
                </a>
                <a class="btn btn-success getRequest" href="#"
                   data-action="/competitions/figures/approve-all-classes"
                   data-id="<?= $model->id ?>" title="Подтвердить">
                    Подтвердить все новые классы
                </a>
            </div>
        </div>
	<?php } ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'yearId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'yearId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите год...',
					]
				]),
				'value'     => function (\common\models\FigureTime $item) {
					return $item->year->year;
				}
			],
			'attribute' => 'dateForHuman',
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'athleteId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->all(), 'id', 'lastName'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите фамилию...',
					]
				]),
				'value'     => function (\common\models\FigureTime $item) {
					$athlete = $item->athlete;
					
					return $athlete->getFullName() . ', ' . $athlete->city->title . '<br>'
						. $item->motorcycle->getFullTitle();
				}
			],
			[
				'attribute' => 'athleteClassId',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\FigureTime $item) {
					return $item->athleteClass->title;
				}
			],
			'timeForHuman',
			[
				'attribute' => 'fine',
				'filter'    => false
			],
			'resultTimeForHuman',
			[
				'attribute' => 'percent',
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					return $item->percent . '%';
				}
			],
			[
				'attribute' => 'newAthleteClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					$html = '';
					if ($item->newAthleteClassId) {
						$html = $item->newAthleteClass->title;
						if ($item->newAthleteClassStatus == \common\models\FigureTime::NEW_CLASS_STATUS_NEED_CHECK) {
							$html .= '<div class="newClass">';
							$html .= '<a class="btn btn-danger getRequest" href="#"
                           data-action="/competitions/figures/cancel-class"
                           data-id="' . $item->id . '" title="Отменить">
                            <span class="fa fa-remove"></span>
                        </a>';
							$html .= '<a class="btn btn-success getRequest" href="#"
                           data-action = "/competitions/figures/approve-class"
                           data-id = "' . $item->id . '" title = "Подтвердить" >
                            <span class="fa fa-check" ></span >
                        </a > ';
							$html .= '</div>';
						}
					}
					
					return $html;
				}
			],
            [
	            'format'    => 'raw',
	            'value'     => function (\common\models\FigureTime $item) {
		            return Html::a('<span class = "fa fa-edit"></span>', ['update-time', 'id' => $item->id],
                        ['class' => 'btn btn-primary']);
	            }
            ]
		],
	]); ?>
</div>
