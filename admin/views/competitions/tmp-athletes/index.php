<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\editable\Editable;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpAthletesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на регистрацию в личном кабинете';
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
                    Если есть верное совпадение - создайте кабинет спортсмену. Мы рекомендуем нажимать эту кнопку, даже
                    если кабинет
                    уже был создан - в таком случае, человеку просто будет отправлен новый пароль.
                </li>
                <li>
                    Если вы создаёте кабинет существующему спортсмену, у которого в системе ещё нет мотоцикла,
                    указанного в заявке,
                    то после нажатия на кнопку "создать кабинет этому спортсмену" вам будет предложено выбрать мотоциклы
                    для добавления.
                    Если же все мотоциклы уже есть в системе, но окно всё равно появилось - просто нажмите кнопку
                    "Добавить", не
                    выбрав при этом ни одну запись.
                </li>
                <li>
                    Мы рекомендуем стараться не отклонять заявки. Эта кнопка может быть актуальна только в том случае,
                    если, например, человек оставил заявку 2 раза подряд - тогда, конечно, нет смысла подтверждать обе.
                </li>
            </ol>
        </li>
        <li>
            Если под городом есть выпадающий список с текстом "выберите город" - необходимо найти в нём город
            спортсмена. Если его нет - создайте город на странице
            "<?= Html::a('Города', ['/competitions/help/cities']) ?>"
        </li>
    </ul>
</div>

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
						'name'          => 'email',
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
					
					$html .= '<br>';
					$motorcycles = $athlete->getMotorcycles();
					$motorcyclesTitle = '<ul>';
					foreach ($motorcycles as $i => $motorcycle) {
						$motorcyclesTitle .= '<li><div id="tmp-motorcycle-' . $athlete->id . '-' . $i . '">';
						$motorcyclesTitle .= $motorcycle['mark'] . ' ' . $motorcycle['model'];
						$motorcyclesTitle .= '<br>' . $motorcycle['cbm'] . 'см<sup>3</sup>, ' . $motorcycle['power'] . 'л.с.';
						$motorcyclesTitle .= '<br>' . ((isset($motorcycle['isCruiser']) && $motorcycle['isCruiser'] === 1)
								? 'круизёр' : 'не круизёр');
						$motorcyclesTitle .= '<br>' .
							'</div><a href="#" data-id="' . $athlete->id . '" data-motorcycle-id="' . $i
                            . '" data-mode="athlete" class="changeTmpMotorcycle">изменить</a>';
						$motorcyclesTitle .= '<hr></li>';;
					}
					$motorcyclesTitle .= '</ul>';
					$html .= $motorcyclesTitle;
					
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
						$html = $athlete->city . '<br>';
						$html .= Html::beginForm('', 'post', ['id' => 'cityForNewAthlete' . $athlete->id]);
						$html .= Html::hiddenInput('id', $athlete->id);
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
									'data'     => new JsExpression('function(params) { return {title:params.term, countryId:' . $athlete->countryId . '}; }')
								],
								'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
								'templateResult'     => new JsExpression('function(city) { return city.text; }'),
								'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
							],
							'pluginEvents'  => [
								'change' => 'function(e){
				cityForNewAthlete(' . $athlete->id . ');
			}',
							],
						]);
						$html .= Html::endForm();
						
						return $html;
					}
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
							$result .= Html::a($coincidenceAthlete->getFullName(), ['/competitions/athlete/view', 'id' => $coincidenceAthlete->id], ['target' => '_blank'])
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
										'class'                => 'btn btn-my-style btn-orange small registrationOldAthlete',
										'data-tmp-id'          => $athlete->id,
										'data-athlete-id'      => $coincidenceAthlete->id,
										'data-all-motorcycles' => (int)$coincidence['hasAllMotorcycles']
									]);
							$result .= '<hr>';
						}
						
						$result .= '<br>';
					} else {
						$result .= 'Совпадений по имени и фамилии не найдено<br>';
					}
					
					$result .= '<a href="#" data-last-name="' . $athlete->lastName . '" class="findByFirstName btn btn-my-style small btn-default">
					список всех спортсменов с такой фамилией</a>';
					
					return $result;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\TmpAthlete $athlete) {
					return Html::a('Создать нового спортсмена и зарегистрировать',
							['/competitions/tmp-athletes/registration-new-athlete'],
							[
								'class'       => 'btn btn-my-style btn-green small getRequestWithConfirm',
								'data-action' => '/competitions/tmp-athletes/registration-new-athlete',
								'data-text'   => 'Уверены, что хотите создать нового спортсмена?',
								'data-id'     => $athlete->id,
							]) . '<br>'
						. Html::a('Отклонить заявку',
							['/competitions/tmp-athletes/cancel'],
							[
								'class'       => 'btn btn-my-style btn-red small getRequestWithConfirm',
								'data-action' => '/competitions/tmp-athletes/cancel',
								'data-text'   => 'Уверены, что хотите отклонить заявку?',
								'data-id'     => $athlete->id,
							]);
				}
			],
		],
	]); ?>
</div>

<div class="modalList"></div>
<div class="modalMotorcycles"></div>
<div class="modalChangeTmpMotorcycle"></div>