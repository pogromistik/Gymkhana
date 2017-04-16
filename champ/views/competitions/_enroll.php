<?php
/**
 * @var \common\models\Stage $stage
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\City;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;

$participant = \common\models\TmpParticipant::createForm($stage->id);
$championship = $stage->championship;
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
		<?php $form = ActiveForm::begin(['options' => ['class'       => 'newRegistration',
		                                               'data-action' => 'add-unauthorized-registration']]) ?>
        <div class="modal-body">
            <div class="help-for-athlete">
                <small>
                    Если Вы зарегистрированы у нас на сайте - пожалуйста, сперва
					<?= \yii\bootstrap\Html::a('ВОЙДИТЕ В ЛИЧНЫЙ КАБИНЕТ', ['/site/login']) ?>
                </small>
            </div>
			
			<?= $form->field($participant, 'stageId')->hiddenInput()->label(false)->error(false) ?>
			<?= $form->field($participant, 'championshipId')->hiddenInput(['id' => 'championshipId'])->label(false)->error(false) ?>

            <h4 class="text-center">Укажите информацию о себе</h4>
			<?= $form->field($participant, 'lastName')->textInput(['placeholder' => 'Ваша фамилия']) ?>
			<?= $form->field($participant, 'firstName')->textInput(['placeholder' => 'Полное имя']) ?>
			<?php if (!$participant->countryId) { ?>
				<?php $participant->countryId = 1 ?>
			<?php } ?>
			<?= $form->field($participant, 'countryId')->widget(Select2::classname(), [
				'data'    => \common\models\Country::getAll(true),
				'options' => [
					'placeholder' => 'Выберите страну...',
					'id'          => 'country-id',
				],
			]); ?>
			
			<?php
			$url = \yii\helpers\Url::to(['/help/city-list']);
			$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $participant->countryId])
				->orderBy(['title' => SORT_ASC])->limit(50)->all(),
				'id', 'title');
			?>

            <div class="registration-city">
                <div id="city-list">
					<?= $form->field($participant, 'cityId')->widget(DepDrop::classname(), [
						'data'           => $participant,
						'options'        => ['placeholder' => 'Выберите город...'],
						'type'           => DepDrop::TYPE_SELECT2,
						'select2Options' => [
							'pluginOptions' => [
								'allowClear'         => true,
								'minimumInputLength' => 3,
								'language'           => [
									'errorLoading' => new JsExpression("function () { return 'Поиск результатов...'; }"),
								],
								'ajax'               => [
									'url'      => $url,
									'dataType' => 'json',
									'data'     => new JsExpression('function(params) { return {title:params.term, countryId:$("#country-id").val(), championshipId: $("#championshipId").val()}; }')
								],
								'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
								'templateResult'     => new JsExpression('function(city) { return city.text; }'),
								'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
							],
						],
						'pluginOptions'  => [
							'depends'     => ['country-id'],
							'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
							'loadingText' => 'Для выбранной страны нет городов...',
							'placeholder' => 'Выберите город...',
						]
					]); ?>
                </div>
				<?php if (!$championship->isClosed) { ?>
                    <div class="small">
                        <a href="#" class="list" id="cityNotFound">Нажмите, если вашего города нет в списке</a>
                    </div>
				<?php } else { ?>
                    <div class="small">
                        Для регистрации доступны только города областей: <?= $championship->getRegionsFor(true) ?>
                    </div>
				<?php } ?>
                <div id="city-text" class="inactive">
					<?= $form->field($participant, 'city')->textInput(['placeholder' => 'Введите Ваш город и регион', 'id' => 'city-text-input']) ?>
                </div>
            </div>
			<?= $form->field($participant, 'phone')->textInput(['placeholder' => 'Номер телефона']) ?>

            <h4 class="text-center">Укажите мотоцикл, на котором будете участвовать</h4>
			<?= $form->field($participant, 'motorcycleMark')->textInput(['placeholder' => 'Марка, напр. kawasaki']) ?>
			<?= $form->field($participant, 'motorcycleModel')->textInput(['placeholder' => 'Модель, напр. ER6-F']) ?>

            <div class="alerts"></div>

            <h4 class="text-center">Желаемый номер</h4>
            <div class="help-for-athlete">
                <small>
                    Выберите значение от <?= $championship->minNumber ?> до <?= $championship->maxNumber ?>
                    или оставьте поле пустым
                </small>
            </div>
			<?= $form->field($participant, 'number')->textInput(['placeholder' => 'номер участника'])->label(false) ?>
            <a href="#" class="freeNumbersList" data-id="<?= $stage->id ?>">Посмотреть список свободных номеров</a>
        </div>
        <div class="alert alert-danger" style="display: none"></div>
        <div class="alert alert-success" style="display: none"></div>
        <div class="modal-footer">
            <div class="form-text"></div>
            <div class="button">
				<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-lg btn-block btn-dark']) ?>
            </div>

            <div class="free-numbers text-left">
                <hr>
                <h4 class="text-center">Свободные номера</h4>
                <div class="list"></div>
            </div>
        </div>
		<?php $form->end() ?>
    </div>
</div>