<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpParticipant;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpParticipantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на участие, требующие одобрения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tmp-participant-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'championshipId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'championshipId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Championship::find()->all(), 'id', 'title'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите чемпионат...',
					]
				]),
				'value'     => function (TmpParticipant $participant) {
					$result = $participant->championship->title;
					$result .= '<br>';
					$result .= $participant->stage->title;
					
					return $result;
				}
			],
			[
				'label'  => 'Данные о спортсмене',
				'format' => 'raw',
				'value'  => function (TmpParticipant $participant) {
					$result =
						Editable::widget([
							'name'          => 'lastName',
							'value'         => $participant->lastName,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->lastName,
								'placement' => 'right',
							]
						]) . ' ' . Editable::widget([
							'name'          => 'firstName',
							'value'         => $participant->firstName,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->firstName,
								'placement' => 'right',
							]
						]) . ($participant->number ? ', №' . $participant->number : '');
					$result .= '<br>';
					$result .= '<small>' .
						Editable::widget([
							'name'          => 'city',
							'value'         => $participant->city,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->city,
								'placement' => 'right',
							]
						]) . ($participant->phone ? ', ' . $participant->phone : '') . '</small>';
					$result .= '<br>';
					$result .= Editable::widget([
							'name'          => 'motorcycleMark',
							'value'         => $participant->motorcycleMark,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->motorcycleMark,
								'placement' => 'right',
							]
						]) . ' ' . Editable::widget([
							'name'          => 'motorcycleModel',
							'value'         => $participant->motorcycleModel,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->motorcycleModel,
								'placement' => 'right',
							]
						]);
					
					return $result;
				}
			],
			[
				'label'  => 'Совпадения с существующими данными',
				'format' => 'raw',
				'value'  => function (TmpParticipant $participant) {
					$coincidences = $participant->getCoincidences();
					$result = '';
					if ($coincidences) {
						foreach ($coincidences as $data) {
							/** @var \common\models\Athlete $athlete */
							$athlete = $data['athlete'];
							$result .= $athlete->getFullName() . ', ' . $athlete->city->title;
							$result .= ' ' . Html::a('Зарегистрировать на новом мотоцикле',
								['competitions/tmp-participant/add-motorcycle-and-registration'],
								[
									'class'              => 'btn btn-info addMotorcycleAndRegistration',
									'data-tmp-id'        => $participant->id,
									'data-athlete-id'    => $athlete->id,
								]);
							
							$result .= '<br>';
							foreach ($data['motorcycles'] as $motorcycleData) {
								/** @var \common\models\Motorcycle $motorcycle */
								$motorcycle = $motorcycleData['motorcycle'];
								$result .= $motorcycle->getFullTitle();
								if ($motorcycleData['isCoincidences']) {
									$result .= '<span class="fa fa-check success"></span>';
								} else {
									$result .= ' ' . Html::a('Зарегистрировать на этом мотоцикле',
											['competitions/tmp-participant/registration'],
											[
												'class'              => 'btn btn-default registrationAthlete',
												'data-tmp-id'        => $participant->id,
												'data-athlete-id'    => $athlete->id,
												'data-motorcycle-id' => $motorcycle->id
											]);
								}
							}
							/** @var \common\models\Participant[] $requests */
							$requests = $data['requests'];
							if ($requests) {
								$result .= '<br><b>Спортсмен уже оставлял заявку на участие:</b><br>';
								foreach ($requests as $request) {
									$result .= 'на ' . $request->motorcycle->getFullTitle();
								}
							}
							$result .= '<hr>';
						}
						$result .= '<br>';
					}
					$result .= '<a href="#" data-last-name="' . $participant->lastName . '" class="findByFirstName">
					список всех спортсменов с такой фамилией</a>';
					
					return $result;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (TmpParticipant $participant) {
					$html = '<div class = "pb-10">' . Html::a('Добавить и зарегистрировать',
							['/competitions/tmp-participant/add-and-registration', 'id' => $participant->id],
							['class' => 'btn btn-success addAndRegistration', 'data-id' => $participant->id]) . '</div>';
					$html .= '<div class = "pb-10">' . Html::a('Отменить заявку',
							['/competitions/tmp-participant/cancel', 'id' => $participant->id],
							['class' => 'btn btn-warning cancelTmpParticipant', 'data-id' => $participant->id]) . '</div>';
					
					return $html;
				}
			]
		],
	]); ?>
</div>

<div class="modalList"></div>
