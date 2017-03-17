<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpAthletesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на регистрацию в личном кабинете';
?>
<div class="tmp-athlete-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'label'  => 'Спортсмен',
				'format' => 'raw',
				'value'  => function (\common\models\TmpAthlete $athlete) {
					$html = $athlete->getFullName() . ', ' . $athlete->country->title;
					$html .= '<br>';
					$html .= Editable::widget([
						'name'          => 'city',
						'value'         => $athlete->email,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $athlete->id,
							'value'     => $athlete->email,
							'placement' => 'right',
						]
					]);
					if ($athlete->phone) {
						$html .= '<br>';
						$html .= $athlete->phone;
					}
					
					return $html;
				}
			],
			[
				'attribute' => 'city',
				'format'    => 'raw',
				'value'     => function (\common\models\TmpAthlete $athlete) {
					if ($athlete->cityId) {
						return $athlete->city;
					} else {
						return Editable::widget([
							'name'          => 'city',
							'value'         => $athlete->city,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $athlete->id,
								'value'     => $athlete->city,
								'placement' => 'right',
							]
						]);
					}
				}
			],
			[
				'attribute' => 'motorcycles',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\TmpAthlete $athlete) {
					$motorcycles = $athlete->getMotorcycles();
					$titles = [];
					foreach ($motorcycles as $motorcycle) {
						$titles[] = $motorcycle['mark'] . ' ' . $motorcycle['model'];
					}
					
					return implode('<br>', $titles);
				}
			],
			[
				'label'  => 'Совпадения с существующими данными',
				'format' => 'raw',
				'value'  => function (\common\models\TmpAthlete $athlete) {
					$coincidences = $athlete->getCoincidences();
					$result = '';
					if ($coincidences) {
						foreach ($coincidences as $coincidence) {
							/** @var \common\models\Athlete $coincidenceAthlete */
							$coincidenceAthlete = $coincidence['athlete'];
							$result .=  Html::a($coincidenceAthlete->getFullName(), ['/competitions/athlete/view', 'id' => $coincidenceAthlete->id], ['target' => '_blank'])
								 . ', ' . $coincidenceAthlete->country->title;
							$result .= '<br>' . $coincidenceAthlete->city->title;
							if ($coincidenceAthlete->email) {
								$result .= '<br>' . $coincidenceAthlete->email;
							}
							if ($coincidenceAthlete->hasAccount) {
								$result .= '<br><small>кабинет уже был создан</small>';
							}
							
							$result .= '<br>' . Html::a('создать кабинет этому спортсмену',
									['/competitions/tmp-athletes/registration-old-athlete'],
									[
										'class'                  => 'btn btn-info registrationOldAthlete',
										'data-tmp-id'            => $athlete->id,
										'data-athlete-id'        => $coincidenceAthlete->id,
										'data-all-motorcycles' => (int)$coincidence['hasAllMotorcycles']
									]);
							$result .= '<hr>';
						}
						
						$result .= '<br>';
					} else {
						$result .= 'Совпадений по имени и фамилии не найдено<br>';
					}
					
					$result .= '<a href="#" data-last-name="' . $athlete->lastName . '" class="findByFirstName">
					список всех спортсменов с такой фамилией</a>';
					
					return $result;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\TmpAthlete $athlete) {
					return Html::a('Создать и зарегистрировать нового спортсмена',
						['competitions/tmp-athletes/registration-old-athlete'],
						[
							'class'       => 'btn btn-success registrationOldAthlete',
							'data-tmp-id' => $athlete->id,
						]);
				}
			],
		],
	]); ?>
</div>

<div class="modalList"></div>
<div class="modalMotorcycles"></div>