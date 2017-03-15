<?php
/**
 * @var \common\models\Stage $stage
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\City;

$participant = \common\models\TmpParticipant::createForm($stage->id);
$championship = $stage->championship;
?>

<div class="modal fade" id="enrollForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php $form = ActiveForm::begin(['options' => ['class' => 'newRegistration',
            'data-action' => 'add-unauthorized-registration']]) ?>
			<div class="modal-body">
                <div class="help-for-athlete">
                    <small>
                        Если Вы зарегистрированы у нас на сайте - пожалуйста, сперва
						<?= \yii\bootstrap\Html::a('ВОЙДИТЕ В ЛИЧНЫЙ КАБИНЕТ', ['/site/login']) ?>
                    </small>
                </div>
                
				<?= $form->field($participant, 'stageId')->hiddenInput()->label(false)->error(false) ?>
				<?= $form->field($participant, 'championshipId')->hiddenInput()->label(false)->error(false) ?>
                
                <h4 class="text-center">Укажите информацию о себе</h4>
				<?= $form->field($participant, 'lastName')->textInput(['placeholder' => 'Ваша фамилия']) ?>
				<?= $form->field($participant, 'firstName')->textInput(['placeholder' => 'Полное имя']) ?>
                <?php if (!$participant->countryId) { ?>
                    <?php $participant->countryId = 1 ?>
                <?php } ?>
				<?= $form->field($participant, 'countryId')->dropDownList(\common\models\Country::getAll(true)); ?>
				<?= $form->field($participant, 'city')->textInput(['placeholder' => 'Ваш город']) ?>
				<?= $form->field($participant, 'phone')->textInput(['placeholder' => 'Номер телефона']) ?>
                
				<h4 class="text-center">Укажите мотоцикл, на котором будете участвовать</h4>
				<?= $form->field($participant, 'motorcycleMark')->textInput(['placeholder' => 'Марка, напр. kawasaki']) ?>
				<?= $form->field($participant, 'motorcycleModel')->textInput(['placeholder' => 'Модель, напр. ER6-F']) ?>
                
                <h4 class="text-center">Желаемый номер</h4>
                <div class="help-for-athlete">
                    <small>
                        Выберите значение от <?= $championship->minNumber ?> до <?= $championship->maxNumber ?>
                        или оставьте поле пустым
                    </small>
                </div>
				<?= $form->field($participant, 'number')->textInput(['placeholder' => 'номер участника'])->label(false) ?>
                <a href="#" class="freeNumbersList" data-id = "<?= $stage->id ?>">Посмотреть список свободных номеров</a>
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
</div>