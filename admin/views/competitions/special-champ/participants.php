<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;
use common\models\RequestForSpecialStage;

/**
 * @var \yii\web\View                                     $this
 * @var common\models\search\RequestForSpecialStageSearch $searchModel
 * @var \yii\data\ActiveDataProvider                      $dataProvider
 * @var \common\models\SpecialStage                       $stage
 * @var \admin\models\ParticipantForm                     $formModel
 * @var array                                             $forSearch
 */

$this->title = $stage->title . ': ' . 'Участники';
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/special-champ/view-stage', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = 'Участники';
?>
<div class="request-for-special-stage-index">

    <div class="request-for-special-stage-form">
		
		<?php $form = ActiveForm::begin(); ?>
		
		<?= $form->field($formModel, 'stageId')->hiddenInput()->label(false)->error(false) ?>
		
		<?= $form->field($formModel, 'athleteId')->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
				return $item->lastName . ' ' . $item->firstName . ' (' . $item->city->title . ')';
			}),
			'options' => [
				'placeholder' => 'Выберите спортсмена...',
				'id'          => 'athlete-id',
			]
		]) ?>
		
		<?= $form->field($formModel, 'motorcycleId')->widget(\kartik\widgets\DepDrop::className(), [
			'options'       => ['id' => 'motorcycle-id'],
			'data'          => ArrayHelper::map(\common\models\Motorcycle::findAll(['athleteId' => $formModel->athleteId]), 'id', function (\common\models\Motorcycle $item) {
				return $item->mark . ' ' . $item->model;
			}),
			'pluginOptions' => [
				'depends'     => ['athlete-id'],
				'placeholder' => 'Выберите мотоцикл...',
				'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category')
			]
		]) ?>
		
		<?= $form->field($formModel, 'dateHuman')->widget(DatePicker::classname(), [
			'options'       => ['placeholder' => 'Введите дату заезда'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy',
			]
		]) ?>
		
		<?= $form->field($formModel, 'timeHuman')->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'setTime',
				'class' => 'form-control',
				'type'  => 'tel'
			]
		]) ?>
		
		<?= $form->field($formModel, 'fine')->textInput() ?>
		
		<?= $form->field($formModel, 'videoLink')->textInput() ?>

        <div class="form-group">
			<?= Html::submitButton('Добавить', ['class' => 'btn btn-my-style btn-green']) ?>
        </div>
		
		<?php ActiveForm::end(); ?>

    </div>


    <div class="small">
        <div class="color-div need-clarification-participant"></div>
        - результаты, требующие модерации;
        <div class="color-div inactive-participant"></div>
        - отклоненные результаты;
        <div class="color-div out-participant"></div>
        - неактуальные результаты.
    </div>
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => function (\common\models\RequestForSpecialStage $item) {
			if ($item->status === \common\models\RequestForSpecialStage::STATUS_NEED_CHECK) {
				return ['class' => 'need-clarification-participant'];
			} elseif ($item->status === \common\models\RequestForSpecialStage::STATUS_CANCEL) {
				return ['class' => 'inactive-participant'];
			} elseif ($item->status === \common\models\RequestForSpecialStage::STATUS_IN_ACTIVE) {
				return ['class' => 'out-participant'];
			}
		},
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'athleteId',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'athleteId',
					'data'          => $forSearch,
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Укажите фамилию или имя...',
					]
				]),
				'format'    => 'raw',
				'value'     => function (RequestForSpecialStage $item) {
					return $item->athleteId ?
						Html::a($item->athlete->getFullName() . ', ' .
                            $item->athleteClass->title, ['/competitions/athlete/view', 'id' => $item->athleteId]) : '';
				}
			],
			[
				'attribute' => 'motorcycleId',
				'filter'    => false,
				'value'     => function (RequestForSpecialStage $item) {
					return $item->motorcycleId ? $item->motorcycle->getFullTitle() : '';
				}
			],
			'timeHuman',
			[
				'attribute' => 'fine',
				'filter'    => false
			],
			[
				'attribute' => 'videoLink',
				'filter'    => false
			],
			[
				'format' => 'raw',
				'value'  => function (RequestForSpecialStage $item) {
					if (!$item->athleteId) {
						return '';
					}
					
					return Html::a('<span class="fa fa-edit"></span>', ['update-participant', 'id' => $item->id],
						['class' => 'btn btn-my-style btn-blue small']);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (RequestForSpecialStage $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete-participant', 'id' => $item->id],
						['class' => 'btn btn-my-style btn-red small',
						 'data'  => [
							 'confirm' => 'Уверены, что хотите полностью удалить результат?'
						 ]]);
				}
			]
		],
	]); ?>
</div>