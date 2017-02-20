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
					        $result .= $athlete->getFullName().', ' . $athlete->city->title;
					        $result .= '<br>';
					        foreach ($data['motorcycles'] as $motorcycleData) {
					            /** @var \common\models\Motorcycle $motorcycle */
					            $motorcycle = $motorcycleData['motorcycle'];
					            $result .= $motorcycle->getFullTitle();
					            if ($motorcycleData['isCoincidences']) {
					                $result .= '<span class="fa fa-check success"></span>';
                                }
                            }
                            /** @var \common\models\Participant[] $requests */
                            $requests = $data['requests'];
                            if ($requests) {
                                $result .= '<br>Спортсмен уже оставлял заявку на участие:<br>';
                                foreach ($requests as $request) {
                                    $result .= 'на ' . $request->motorcycle->getFullTitle();
                                }
                            }
                            $result .= '<hr>';
                        }
                        $result .= '<br>';
                    }
					$result .= 'список всех спортсменов с такой фамилией';
					
					return $result;
				}
			],
            [
                'format' => 'raw'
            ]
		],
	]); ?>
</div>
