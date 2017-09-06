<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
$contact = new \common\models\Feedback();
?>

<div class="modal fade" id="feedbackForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= \Yii::t('app', 'Обратная связь') ?></h4>
            </div>
			<?php $form = ActiveForm::begin(['options' => ['class' => 'newQuestion']]) ?>
            <div class="modal-body">
                <div class="alert alert-danger" style="display: none"></div>
                <div class="alert alert-success" style="display: none"></div>
                <?php if (!\Yii::$app->user->isGuest) {
                    $contact->athleteId = \Yii::$app->user->identity->id;
                    $contact->username = \Yii::$app->user->identity->lastName . ' ' .\Yii::$app->user->identity->firstName;
                    ?>
	                <?= $form->field($contact, 'username')->hiddenInput()->label(false)->error(false) ?>
	                <?= $form->field($contact, 'athleteId')->hiddenInput()->label(false)->error(false) ?>
                <?php } else { ?>
	                <?= $form->field($contact, 'username')->textInput(['placeholder' => \Yii::t('app', 'ваше имя')]) ?>
                <?php } ?>
				<?= $form->field($contact, 'phoneOrMail')->textInput(['placeholder' => \Yii::t('app', 'телефон или e-mail')])->label(\Yii::t('app', 'Контакты для связи')) ?>
				<?= $form->field($contact, 'text')->textarea(['rows' => 3]) ?>
            </div>
            <div class="modal-footer">
                <div class="form-text"></div>
                <div class="button">
					<?= Html::submitButton(\Yii::t('app', 'Отправить'), ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
			<?php $form->end() ?>
        </div>
    </div>
</div>