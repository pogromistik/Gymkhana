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
	->andWhere(['newAthleteClassStatus' => \common\models\FigureTime::NEW_CLASS_STATUS_NEED_CHECK])->all();
$newRecords = $model->getResults()->andWhere(['not', ['recordType' => null]])
	->andWhere(['recordStatus' => \common\models\FigureTime::NEW_RECORD_NEED_CHECK])->all();
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
				'content' => $this->render('_form', ['model' => $model]),
				'options' => ['class' => 'panel panel-my-style panel-blue']
			],
		]
	]);
	?>

    <h3>Результаты</h3>

    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        <ul>
            <li>
                Обратите внимание, что новые классы спортсменов требуют подтверждения. Если спортсмены повысят класс -
                появятся
                кнопки для его подтверждения и отклонения. Можно подтвердить\отклонить как все результаты сразу, так и один
                конкретный. Если какие-то результаты вызывают у вас подозрение - рекомендуем проверить их на предмет
                опечаток и при
                необходимости исправить.
            </li>
            <li>
                Если вы подтвердили новый класс, а потом выяснилось, что в результате допущена опечатка и фактический класс
                спортсмена ниже - измените класс в профиле спортсмена или на
                странице "<?= Html::a('изменить класс спортсмена', ['/competitions/athlete/change-class']) ?>".
            </li>
        </ul>
    </div>
	
	<?php Modal::begin([
		'header'       => '<h2>Выберите дату заездов</h2>',
		'toggleButton' => [
			'label' => 'Добавить результаты',
			'class' => 'btn btn-my-style btn-green'
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
			<?= Html::submitButton(\Yii::t('app', 'ОК'), ['class' => 'btn btn-my-style btn-gray']) ?>
        </div>
    </div>
	<?= Html::endForm() ?>
	<?php Modal::end() ?>
	
	
	<?php if ($newClasses) { ?>
        <div class="text-right newClass">
            <div class="pb-10">
                <a class="btn btn-my-style btn-small btn-red getRequest" href="#"
                   data-action="/competitions/figures/cancel-all-classes"
                   data-id="<?= $model->id ?>" title="Отменить">
                    Отменить все новые неподтверждённые классы
                </a>
                <a class="btn btn-my-style btn-light-green getRequest" href="#"
                   data-action="/competitions/figures/approve-all-classes"
                   data-id="<?= $model->id ?>" title="Подтвердить">
                    Подтвердить все новые классы
                </a>
            </div>
        </div>
	<?php } ?>
	
	<?php if ($newRecords) { ?>
        <div class="text-right newClass">
            <div class="pb-10">
                <a class="btn btn-my-style btn-small btn-red getRequestWithConfirm" href="#"
                   data-action="/competitions/figures/cancel-all-records"
                   data-text="Уверены, что хотите отменить все новые неподтверждённые рекорды?"
                   data-id="<?= $model->id ?>" title="Отменить">
                    Отменить все новые рекорды
                </a>
            </div>
        </div>
	<?php } ?>
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [['class' => 'yii\grid\SerialColumn'],
			
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
			[
				'attribute' => 'dateForHuman',
				'format'    => 'raw',
                'value' => function (\common\models\FigureTime $item) {
	                if (\Yii::$app->user->can('developer')) {
	                    return Html::a($item->dateForHuman, ['/competitions/developer/logs',
                            'modelClass' => \common\models\FigureTime::class, 'modelId' => $item->id],
		                    ['class' => 'dev-logs']);
                    }
                    return $item->dateForHuman;
                }
            ],
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'athleteId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Athlete::find()->orderBy(['lastName' => SORT_ASC])->all(), 'id', 'lastName'),
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
					$html = $athlete->getFullName() . ', ' . $athlete->city->title . '<br>'
						. $item->motorcycle->getFullTitle();
					if ($item->videoLink) {
						$html .= '<br>';
						$html .= '<a href="' . $item->videoLink . '" target="_blank">Видео заезда</a>';
					}
					
					return $html;
				}
			],
			[
				'attribute' => 'athleteClassId',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\FigureTime $item) {
					return $item->athleteClassId ? $item->athleteClass->title : '';
				}
			],
			'timeForHuman',
			[
				'attribute' => 'fine',
				'filter'    => false
			],
			[
				'attribute' => 'resultTime',
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					return $item->resultTimeForHuman;
				}
			],
			[
				'attribute' => 'percent',
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					return $item->percent . '%';
				}
			],
			[
				'attribute' => 'actualPercent',
				'visible'   => $model->severalRecords,
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					return ($item->actualPercent) ? $item->actualPercent . '%' : '';
				}
			],
			[
				'attribute' => 'newAthleteClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\FigureTime $item) {
					$html = '';
					if ($item->newAthleteClassId) {
						$html = '<div class="text-center">';
						$html .= $item->newAthleteClass->title;
						$html .= '</div>';
						if ($item->newAthleteClassStatus == \common\models\FigureTime::NEW_CLASS_STATUS_NEED_CHECK) {
							$html .= '<div class="newClass text-center">';
							$html .= '<a class="btn btn-my-style btn-red getRequest" href="#"
                           data-action="/competitions/figures/cancel-class"
                           data-id="' . $item->id . '" title="Отменить">
                            <span class="fa fa-remove"></span>
                        </a>';
							$html .= '<a class="btn btn-my-style btn-light-green getRequest" href="#"
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
			['format' => 'raw',
			 'value'  => function (\common\models\FigureTime $item) {
				 $html = '';
				 if ($item->recordType) {
					 $html = '<div class="small text-center">';
					 $html .= \common\models\FigureTime::$recordsTitle[$item->recordType];
					 $html .= '</div>';
					 if ($item->recordStatus == \common\models\FigureTime::NEW_RECORD_NEED_CHECK) {
						 if ($item->recordStatus == \common\models\FigureTime::NEW_RECORD_NEED_CHECK) {
							 $html .= '<div class="newClass text-center">';
							 $html .= '<a class="btn btn-my-style btn-red getRequestWithConfirm" href="#"
                           data-action="/competitions/figures/cancel-record"
                           data-text="Уверены, что хотите отменить этот рекорд?"
                           data-id="' . $item->id . '" title="Отменить">
                            <span class="fa fa-remove"></span>
                        </a>';
							 $html .= '<a class="btn btn-my-style btn-light-green getRequestWithConfirm" href="#"
							 data-text="Уверены, что хотите установить новый рекорд для фигуры?"
                           data-action = "/competitions/figures/approve-record"
                           data-id = "' . $item->id . '" title = "Подтвердить" >
                            <span class="fa fa-check" ></span >
                        </a > ';
							 $html .= '</div>';
						 }
					 }
					
				 }
				
				 return $html;
			 }
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\FigureTime $item) {
					return Html::a('<span class = "fa fa-edit"></span>', ['update-time', 'id' => $item->id],
						['class' => 'btn btn-my-style btn-blue']);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('developer'),
				'value'   => function (\common\models\FigureTime $item) {
					if ($item->newAthleteClassId || $item->recordType) {
						return null;
					}
					
					return Html::a('<span class = "fa fa-remove"></span>', ['delete-time', 'id' => $item->id, 'figureId' => $item->figureId],
						['class' => 'btn btn-my-style btn-red',
						 'data'  => [
							 'confirm' => 'Уверены, что хотите удалить результат спортсмена' . $item->athlete->getFullName() . '?'
						 ]]);
				}
			]
		],
	]); ?>
</div>
