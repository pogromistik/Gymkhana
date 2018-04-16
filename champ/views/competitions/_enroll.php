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
					<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Если Вы зарегистрированы у нас на сайте - пожалуйста, сперва ВОЙДИТЕ В ЛИЧНЫЙ КАБИНЕТ'),
						['/site/login']) ?>
                </small>
            </div>
			
			<?= $form->field($participant, 'stageId')->hiddenInput()->label(false)->error(false) ?>
			<?= $form->field($participant, 'championshipId')->hiddenInput(['id' => 'championshipId'])->label(false)->error(false) ?>

            <h4 class="text-center"><?= \Yii::t('app', 'Укажите информацию о себе') ?></h4>
			<?= $form->field($participant, 'lastName')->textInput(['placeholder' => \Yii::t('app', 'Ваша фамилия')]) ?>
			<?= $form->field($participant, 'firstName')->textInput(['placeholder' => \Yii::t('app', 'Полное имя')]) ?>
			<?php if (!$participant->countryId) { ?>
				<?php $participant->countryId = 1 ?>
			<?php } ?>
			<?= $form->field($participant, 'countryId')->widget(Select2::classname(), [
				'data'    => \common\models\Country::getAll(true),
				'options' => [
					'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
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
						'options'        => ['placeholder' => \Yii::t('app', 'Выберите город') . '...'],
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
                        <a href="#" class="list"
                           id="cityNotFound"><?= \Yii::t('app', 'Нажмите, если вашего города нет в списке') ?></a>
                    </div>
				<?php } else { ?>
                    <div class="small">
						<?= \Yii::t('app', 'Для регистрации доступны только города областей:') ?> <?= $championship->getRegionsFor(true) ?>
                    </div>
				<?php } ?>
                <div id="city-text" class="inactive">
					<?= $form->field($participant, 'city')->textInput(['placeholder' => \Yii::t('app', 'Введите Ваш город и регион'), 'id' => 'city-text-input']) ?>
                </div>
            </div>
			<?= $form->field($participant, 'phone')->textInput(['placeholder' => \Yii::t('app', 'Номер телефона')]) ?>
			<?= $form->field($participant, 'email')->textInput(['placeholder' => \Yii::t('app', 'Email')]) ?>

            <h4 class="text-center"><?= \Yii::t('app', 'Укажите мотоцикл, на котором будете участвовать') ?></h4>
			<?= $form->field($participant, 'motorcycleMark')->textInput(['placeholder' => \Yii::t('app', 'Марка, напр. kawasaki')]) ?>
			<?= $form->field($participant, 'motorcycleModel')->textInput(['placeholder' => \Yii::t('app', 'Модель, напр. ER6-F')]) ?>
			<?= $form->field($participant, 'cbm')->textInput(['placeholder' => \Yii::t('app', 'Объём, см3')]) ?>
			<?= $form->field($participant, 'power')->textInput(['placeholder' => \Yii::t('app', 'Мощность, л.с.')]) ?>

            <div class="alerts"></div>

            <h4 class="text-center"><?= \Yii::t('app', 'Желаемый номер') ?></h4>
            <div class="help-for-athlete">
                <small>
					<?= \Yii::t('app', 'Выберите значение от {minNumber} до {maxNumber} или оставьте поле пустым', [
						'minNumber' => $championship->minNumber,
						'maxNumber' => $championship->maxNumber
					]) ?>
                </small>
            </div>
			<?= $form->field($participant, 'number')->textInput(['placeholder' => \Yii::t('app', 'номер участника')])->label(false) ?>
            <a href="#" class="freeNumbersList"
               data-id="<?= $stage->id ?>"><?= \Yii::t('app', 'Посмотреть список свободных номеров') ?></a>
        </div>
        <div class="alert alert-danger" style="display: none"></div>
        <div class="alert alert-success" style="display: none"></div>
        <div class="modal-footer">
            <div class="form-text"></div>
            <div class="button">
				<?= Html::submitButton(\Yii::t('app', 'Зарегистрироваться'), ['class' => 'btn btn-lg btn-block btn-dark']) ?>
            </div>

            <div class="free-numbers text-left">
                <hr>
                <h4 class="text-center"><?= \Yii::t('app', 'Свободные номера') ?></h4>
                <div class="list"></div>
            </div>
        </div>
		<?php $form->end() ?>
    </div>
</div>