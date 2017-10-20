<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpParticipant;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpParticipantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на участие, требующие одобрения';
?>

<div class="alert required-alert-info">
    <b>ВНИМАНИЕ!</b> Обращайте внимание на город участников - в найденном совпадении может быть полный тёзка из другого
    города.
</div>

<div class="alert required-alert-info">
    <b>ВНИМАНИЕ!</b> Не забывайте проверять информацию о мотоциклах в заявке - объём, мощность, параметр "круизёр".
</div>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ul>
        <li>
            <b>ВАЖНО!</b> От того, на сколько правильно вы нажмёте кнопку на этой странице, зависит корректность
            дальнейшей статистики для спортсмена.
        </li>
        <li>
            Первые две колонки - данные, которые оставил человек в заявке на регистрацию в кабинете. Третья -
            совпадения с данными, которые уже есть в системе.<br>
            Если совпадений нет - всё просто: нажмите кнопку "создать нового спортсмена и зарегистрировать" (хотя будет
            здорово, если на всякий случай вы предварительно посмотрите список всех спортсменов с такой фамилией, т.к.
            в системе, к примеру, может быть Юрий, а в заявке - Юра).<br>
            Если же совпадение найдено:
            <ol>
                <li>
                    Проанализируйте данные. Например, если город отличается - значит, это другой спортсмен. Если
                    отличается мотоцикл, а у спортсмена в совпадении уже есть личный кабинет - возможно, это тоже его
                    тёзка. Если у вас есть сомнения - попробуйте связаться с человеком по указанному телефону или email
                    и уточнить этот вопрос. Если окажется, что нет ни одного верного совпадения - создайте нового
                    спортсмена.
                </li>
                <li>
                    Если есть верное совпадение, но у спортсмена нет нужного мотоцикла - нажмите "зарегистрировать на
                    новом мотоцикле".
                </li>
                <li>
                    Если есть верное совпадение с указанным мотоциклом, нажмите "зарегистрировать на этом мотоцикле"
                    рядом с нужной записью
                </li>
                <li>
                    Если спортсмен уже подавал ранее заявку на участие на этом же мотоцикле - отклоните новую.
                </li>
            </ol>
        </li>
        <li>
            Если под городом есть выпадающий список с текстом "выберите город" - необходимо найти в нём город
            спортсмена. Если его нет - создайте на странице <?= Html::a('Города', ['/competitions/help/cities']) ?>
        </li>
        <li>
            <b>Внимание!</b> После обработки на этой странице, все неотклоненные заявки переходят в раздел "участники"
            вашего этапа. Они требуют совершения всех тех же действий, что и заявки, оставленные из личного кабинета.
        </li>
    </ul>
</div>

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
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Championship::find()->orderBy(['dateAdded' => SORT_DESC])->all(), 'id', 'title'),
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
				'label'  => 'Заявка спортсмена',
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
					$result .= $participant->country->title;
					$result .= '<br>';
					$result .= '<small>' . $participant->city . '</small>';
					$html = '<br>';
					if (!$participant->cityId) {
						$html .= Html::beginForm('', 'post', ['id' => 'cityForNewParticipant' . $participant->id]);
						$html .= Html::hiddenInput('id', $participant->id);
						$html .= Select2::widget([
							'name'          => 'city',
							'data'          => [],
							'maintainOrder' => true,
							'options'       => ['placeholder' => 'Выберите город...', 'multiple' => false],
							'pluginOptions' => [
								'maximumInputLength' => 10,
								'ajax'               => [
									'url'      => \yii\helpers\Url::to(['/competitions/help/city-list']),
									'dataType' => 'json',
									'data'     => new JsExpression('function(params) { return {title:params.term, countryId:' . $participant->countryId . '}; }')
								],
								'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
								'templateResult'     => new JsExpression('function(city) { return city.text; }'),
								'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
							],
							'pluginEvents'  => [
								'change' => 'function(e){
				cityForNewParticipant(' . $participant->id . ');
			}',
							],
						]);
						$html .= Html::endForm();
						$html .= '<br>';
					}
					$result .= $html;
					$result .= '<small>' . ($participant->phone ? $participant->phone : '') . '</small>';
					$result .= '<br>';
					if ($participant->email) {
						$result .= '<small>' . ($participant->email ? $participant->email : '') . '</small>';
						$result .= '<br>';
					}
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
					$result .= '<br>';
					$result .= Editable::widget([
							'name'          => 'cbm',
							'value'         => $participant->cbm,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->cbm,
								'placement' => 'right',
							]
						]) . 'см<sup>3</sup> ' . Editable::widget([
							'name'          => 'power',
							'value'         => $participant->power,
							'url'           => 'update',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $participant->id,
								'value'     => $participant->power,
								'placement' => 'right',
							]
						]) . 'л.с.';
					$result .= '<br>';
					$result .= 'круизёр? ' . Editable::widget([
						'name'          => 'isCruiser',
						'value'         => $participant->isCruiser ? 'Да' : 'Нет',
						'url'           => 'update',
						'type'          => 'select',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $participant->id,
							'value'     => $participant->isCruiser,
							'placement' => 'right',
							'select'    => [
								'width' => '124px'
							],
							'source'    =>  [
								2 => 'Нет',
								1 => 'Да'
							],
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
										'class'           => 'btn btn-my-style small btn-dirty-blue addMotorcycleAndRegistration',
										'data-tmp-id'     => $participant->id,
										'data-athlete-id' => $athlete->id,
									]);
							
							$result .= '<br>';
							foreach ($data['motorcycles'] as $motorcycleData) {
								/** @var \common\models\Motorcycle $motorcycle */
								$motorcycle = $motorcycleData['motorcycle'];
								$result .= $motorcycle->getFullTitle();
								if ($motorcycleData['isCoincidences']) {
									$result .= '<span class="fa fa-check success"></span>';
								}
								$result .= ' ' . Html::a('Зарегистрировать на этом мотоцикле',
										['competitions/tmp-participant/registration'],
										[
											'class'              => 'btn btn-my-style small btn-light-gray registrationAthlete',
											'data-tmp-id'        => $participant->id,
											'data-athlete-id'    => $athlete->id,
											'data-motorcycle-id' => $motorcycle->id
										]);
								$result .= '<br>';
							}
							/** @var \common\models\Participant[] $requests */
							$requests = $data['requests'];
							if ($requests) {
								$result .= '<br><b>Спортсмен уже оставлял заявку на участие:</b><br>';
								foreach ($requests as $request) {
									$result .= 'на ' . $request->motorcycle->getFullTitle() . '<br>';
								}
							}
							$result .= '<hr>';
						}
						$result .= '<br>';
					}
					$result .= '<a href="#" data-last-name="' . $participant->lastName . '" class="findByFirstName btn btn-my-style small btn-default">
					список всех спортсменов с такой фамилией</a>';
					
					return $result;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (TmpParticipant $participant) {
					$html = '<div>' . Html::a('Создать нового спортсмена и зарегистрировать',
							['/competitions/tmp-participant/add-and-registration', 'id' => $participant->id],
							['class' => 'btn btn-my-style btn-green small addAndRegistration', 'data-id' => $participant->id]) . '</div>';
					$html .= '<div>' . Html::a('Отклонить заявку',
							['/competitions/tmp-participant/cancel', 'id' => $participant->id],
							['class' => 'btn btn-my-style btn-red small cancelTmpParticipant', 'data-id' => $participant->id]) . '</div>';
					
					return $html;
				}
			]
		],
	]); ?>
</div>

<div class="modalList"></div>
